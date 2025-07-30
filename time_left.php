<?php
$config = require __DIR__ . '/vod-config.php';

date_default_timezone_set('Europe/Berlin');

$api_file = $config['files']['api'];
$time_left_file = $config['files']['time_left'];

header('Content-Type: text/plain');

if (!file_exists($api_file) || !file_exists($time_left_file)) {
    echo "Fehlende Dateien.";
    exit;
}

// Werte laden
$api_data = json_decode(file_get_contents($api_file), true);
$start_ts = strtotime($api_data['playback_time']); // fester Startzeitpunkt
$now = time();

// time_left.txt lesen
$time_left = trim(file_get_contents($time_left_file)); // z. B. "6:43:12"
$parts = explode(':', $time_left);
$rest_secs = ($parts[0] * 3600) + ($parts[1] * 60) + ($parts[2] ?? 0);

// Endzeit berechnen: jetzt + restliche Sekunden
$end_ts = $now + $rest_secs;

// Ausgabezeit
$stunden = floor($rest_secs / 3600);
$minuten = floor(($rest_secs % 3600) / 60);
$end_time_formatted = date('H:i:s', $end_ts);

// Wenn das Video schon vorbei ist
if ($rest_secs <= 0) {
    echo "Video ist vorbei.";
    exit;
}

// Ausgabe
echo "$stunden Stunden, $minuten Minuten bis $end_time_formatted";
