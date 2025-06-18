<?php
$config = require __DIR__ . '/vod-config.php';

$zeit = isset($_GET['zeit']) ? intval($_GET['zeit']) : 0;

$add = isset($_GET['add']) ? floatval($_GET['add']) : null;
$remove = isset($_GET['remove']) ? floatval($_GET['remove']) : null;

if (!is_null($add)) {
    $zeit += $add * 60000;
}
if (!is_null($remove)) {
    $zeit -= $remove * 60000;
}

if ($zeit < 0) $zeit = 0;

echo (int)$zeit;
?>