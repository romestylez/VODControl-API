<?php
$config = require __DIR__ . '/vod-config.php';

$path = $config['files']['api'];

if (!file_exists($path) || !filesize($path)) exit;

$data = json_decode(file_get_contents($path), true);
if (!is_array($data) || !isset($data['title'])) exit;

if (preg_match('/G-([A-Z]+)/', $data['title'], $matches)) {
    echo $matches[1];
}
?>