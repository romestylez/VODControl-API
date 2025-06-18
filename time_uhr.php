<?php
$config = require __DIR__ . '/vod-config.php';

$apiPath = $config['files']['api'];
$durPath = $config['files']['duration'];

if (!file_exists($apiPath) || !file_exists($durPath) || !filesize($apiPath) || !filesize($durPath)) exit;

$data = json_decode(file_get_contents($apiPath), true);
$duration = trim(file_get_contents($durPath));

if (!is_array($data) || !isset($data['playback_time']) || !$duration) exit;

$start = DateTime::createFromFormat('H:i:s', $data['playback_time']);
if (!$start) exit;

$parts = explode(':', $duration);
if (count($parts) !== 3) exit;

$interval = new DateInterval(sprintf('PT%dH%dM%dS', $parts[0], $parts[1], $parts[2]));
$start->add($interval);

echo "🕛 bis " . $start->format('H:i:s');
?>