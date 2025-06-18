<?php
$config = require __DIR__ . '/vod-config.php';
$path = $config['vod']['unc_path'];

if (!is_dir($path)) {
    echo "❌ Verzeichnis nicht erreichbar: $path";
    exit;
}

$total = @disk_total_space($path);
$free  = @disk_free_space($path);
$used  = $total - $free;

function format_tb($bytes) {
    return round($bytes / 1024 / 1024 / 1024 / 1024, 2);
}

// VOD-Dateien zählen (.mp4)
$vod_count = 0;
$files = scandir($path);

foreach ($files as $file) {
    if (is_file($path . '\\' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'mp4') {
        $vod_count++;
    }
}

echo "🗄️ Speicher: " . format_tb($total) . " TB | ";
echo "🎞️ VODs: "    . $vod_count . " | ";
echo "❌ Belegt: " . format_tb($used) . " TB | ";
echo "✅ Frei: "   . format_tb($free) . " TB | ";