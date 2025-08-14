<?php
$config = require __DIR__ . '/vod-config.php';

$path = $config['files']['api'];

if (!file_exists($path) || !filesize($path)) {
    echo "Aktuell wird kein VOD abgespielt";
    exit;
}

$data = json_decode(file_get_contents($path), true);
if (!is_array($data) || !isset($data['title']) || trim($data['title']) === '') {
    echo "Aktuell wird kein VOD abgespielt";
    exit;
}

$title = $data['title'];

// Datum extrahieren
if (preg_match('/D-(\d{2}\.\d{2}\.\d{4})$/', $title, $match)) {
    $datum = $match[1];
} else {
    $datum = "";
}

// Titel bereinigen
$title = preg_replace('/ - K-VOD G-[A-Z]+ D-\d{2}\.\d{2}\.\d{4}$/', '', $title);

// Ausgabe
echo $title . ' - VOD - ' . $datum;