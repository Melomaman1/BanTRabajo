<?php
// ───────────────────────────────────────────────────────────
// setup.php – One-time setup
//   Visit this URL once:   https://TU-DOMINIO/simulador/setup.php
//   Telegram will then start sending callback queries to notify.php
// ───────────────────────────────────────────────────────────

include "data.php";

$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host  = $_SERVER['HTTP_HOST']   ?? 'localhost';
$dir   = rtrim(dirname($_SERVER['REQUEST_URI'] ?? '/'), '/');
$hookUrl = "$proto://$host$dir/notify.php";

$api = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=' . urlencode($hookUrl);

$res = @file_get_contents($api);

header('Content-Type: text/plain; charset=utf-8');
echo "Hook URL: $hookUrl\n\n";
echo "Respuesta Telegram:\n$res\n";
