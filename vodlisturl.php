<?php
$config = require __DIR__ . '/vod-config.php';

$paste_file = $config['files']['pastebin_latest'];

header('Content-Type: text/plain');

if (file_exists($paste_file)) {
    $url = trim(file_get_contents($paste_file));
    echo $url;
} else {
    echo "Link nicht verfügbar";
}