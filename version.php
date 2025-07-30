<?php
$config = require __DIR__ . '/vod-config.php';

header('Content-Type: text/plain');

$version = $config['version']['current'];

echo "📺 24x7 VOD Server - Version $version 🖥️ created by romestylez & sm4ck_82 - https://github.com/romestylez/VODControl-API";
