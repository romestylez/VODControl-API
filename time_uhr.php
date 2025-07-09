<?php
$config = require __DIR__ . '/vod-config.php';

// Zeitzone setzen
date_default_timezone_set($config['system']['timezone'] ?? 'Europe/Berlin');

$apiPath = $config['files']['api'];
$timeLeftPath = $config['files']['time_left'];

if (!file_exists($apiPath) || !file_exists($timeLeftPath) || !filesize($apiPath) || !filesize($timeLeftPath)) exit;

$data = json_decode(file_get_contents($apiPath), true);
$time_left = trim(file_get_contents($timeLeftPath));

if (!is_array($data) || !isset($data['playback_time']) || !$time_left) exit;

$now = time();

$parts = explode(':', $time_left);
if (count($parts) < 2) exit;

$rest_secs = ($parts[0] * 3600) + ($parts[1] * 60) + ($parts[2] ?? 0);
$end_ts = $now + $rest_secs;

echo "🕛 bis " . date('H:i', $end_ts);
