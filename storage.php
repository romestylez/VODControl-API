<?php
$config = require __DIR__ . '/vod-config.php';
$vodPath = $config['vod']['unc_path'];

if (!is_dir($vodPath)) {
    echo "❌ Verzeichnis nicht erreichbar: $vodPath";
    exit;
}

function format_tb($bytes) {
    return round($bytes / 1024 ** 4, 2);
}

function folder_size($dir) {
    $size = 0;
    $items = @scandir($dir);
    if (!$items) return 0;

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_file($fullPath)) {
            $size += @filesize($fullPath);
        } elseif (is_dir($fullPath)) {
            $size += folder_size($fullPath);
        }
    }
    return $size;
}

// === Tatsächliche Größe des VOD-Ordners ===
$vodSizeBytes = folder_size($vodPath);

// === Anzahl .mp4-Dateien im VOD-Ordner (nur oberste Ebene) ===
$vodCount = 0;
$entries = @scandir($vodPath);
if ($entries) {
    foreach ($entries as $file) {
        $filePath = $vodPath . DIRECTORY_SEPARATOR . $file;
        if (is_file($filePath) && strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'mp4') {
            $vodCount++;
        }
    }
}

// === Laufwerksinfos ===
$total = @disk_total_space($vodPath);
$free  = @disk_free_space($vodPath);

echo "🗄️ Gesamtspeicher: " . format_tb($total) . " TB | ";
echo "🎞️ VODs: $vodCount | ";
echo "❌ Belegt: " . format_tb($vodSizeBytes) . " TB | ";
echo "✅ Frei: " . format_tb($free) . " TB | ";
