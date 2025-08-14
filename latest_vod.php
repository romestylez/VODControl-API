<?php
$config = require __DIR__ . '/vod-config.php';

$vod_dir = $config['vod']['unc_path'];

// Dateien einlesen
$files = scandir($vod_dir);
$latestFile = '';
$latestTime = 0;

foreach ($files as $file) {
    $fullPath = $vod_dir . '\\' . $file;
    
    if (is_file($fullPath) && pathinfo($file, PATHINFO_EXTENSION) === 'mp4') {
        $mtime = filemtime($fullPath);
        if ($mtime > $latestTime) {
            $latestTime = $mtime;
            $latestFile = $file;
        }
    }
}

if ($latestFile !== '') {
    $name = pathinfo($latestFile, PATHINFO_FILENAME);
    $clean = preg_replace('/\s*-?\s*K?-?VOD.*$/i', '', $name);
    echo "$clean";
} else {
    echo "⚠️ Keine MP4-Dateien gefunden.";
}
?>