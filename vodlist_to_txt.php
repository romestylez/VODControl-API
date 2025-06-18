<?php
$config = require __DIR__ . '/vod-config.php';

$vod_dir = $config['vod']['unc_path'];
$ziel = $config['vod']['vodlist_output'];

$titel = [];
$files = scandir($vod_dir);

foreach ($files as $file) {
    if (is_file($vod_dir . '\\' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'mp4') {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $raw = preg_replace('/\s*-?\s*K?-?VOD.*$/i', '', $name);
        $titel[] = $raw;
    }
}

file_put_contents($ziel, implode("\n", $titel));
echo "✅ VOD-Liste aktualisiert: " . count($titel) . " Titel";
?>