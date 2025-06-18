<?php
$config = require __DIR__ . '/vod-config.php';

date_default_timezone_set('Europe/Berlin');

$api_file = $config['files']['api'];
$duration_file = $config['files']['duration'];

header('Content-Type: text/plain');

// Prüfen ob beide Dateien vorhanden sind
if (!file_exists($api_file) || !file_exists($duration_file)) {
    echo "Fehlende Dateien.";
    exit;
}

// Werte laden
$api_data = json_decode(file_get_contents($api_file), true);
$start = $api_data['playback_time']; // z. B. "09:47:00"
$duration = trim(file_get_contents($duration_file)); // z. B. "3:17:32"

// Zeitpunkte berechnen
$start_ts = strtotime($start);
$parts = explode(':', $duration);
$end_ts = $start_ts + ($parts[0] * 3600) + ($parts[1] * 60) + ($parts[2] ?? 0);
$now = time();
$diff = $end_ts - $now;

// Wenn das Video schon vorbei ist
if ($diff < 0) {
    echo "Video ist vorbei.";
    exit;
}

// Formatierte Ausgabe
$stunden = floor($diff / 3600);
$minuten = floor(($diff % 3600) / 60);
$bis_uhrzeit = date('H:i:s', $end_ts);

echo "$stunden Stunden, $minuten Minuten bis $bis_uhrzeit";