<?php
// ───────────────────────────────────────────────────────────
// state.php – Read / write per-visitor state files
// ───────────────────────────────────────────────────────────

if (!defined('SESS_DIR')) {
    define('SESS_DIR', __DIR__ . '/sessions');
}

if (!is_dir(SESS_DIR)) {
    @mkdir(SESS_DIR, 0775, true);
}

function sess_path($sid) {
    // sanitize sid (only allow safe chars)
    $sid = preg_replace('/[^A-Za-z0-9_\-]/', '', $sid);
    if ($sid === '') return null;
    return SESS_DIR . '/' . $sid . '.json';
}

function sess_load($sid) {
    $path = sess_path($sid);
    if (!$path || !file_exists($path)) return null;
    $raw = @file_get_contents($path);
    $j   = json_decode($raw, true);
    return is_array($j) ? $j : null;
}

function sess_save($sid, array $data) {
    $path = sess_path($sid);
    if (!$path) return false;
    $data['updated_at'] = time();
    return (bool) @file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
}

function sess_new_id() {
    return bin2hex(random_bytes(8));   // 16 hex chars
}
