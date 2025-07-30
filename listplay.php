<?php
$config = require __DIR__ . '/vod-config.php';

// Konfiguration
$vod_dir = $config['vod']['unc_path'];
$playlist_path = $config['vod']['xspf_path'];

// Titel vom Parameter holen
$title = isset($_GET['title']) ? trim($_GET['title']) : '';

header('Content-Type: text/plain');

if (empty($title)) {
    echo "Fehler: Kein Titel übergeben.";
    exit;
}

if (!is_dir($vod_dir)) {
    echo "Fehler: VOD-Verzeichnis nicht gefunden: $vod_dir";
    exit;
}

// Alle MP4-Dateien durchsuchen
$files = glob($vod_dir . '\\*.mp4');

$match = null;
foreach ($files as $file) {
    if (stripos(basename($file), $title) !== false) {
        $match = $file;
        break;
    }
}

if (!$match) {
    echo "Fehler: Kein passendes Video gefunden für Titel: $title";
    exit;
}

// Playlist im XSPF-Format erstellen
$escaped_path = str_replace(['\\', ' '], ['/', '%20'], $match);
$playlist = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
  <trackList>
    <track>
      <location>file:///$escaped_path</location>
      <title>$title</title>
    </track>
  </trackList>
</playlist>
XML;

// Playlist speichern
file_put_contents($playlist_path, $playlist);

echo "Wiedergabeliste erstellt: $playlist_path";
