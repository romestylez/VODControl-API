<?php
$config = require __DIR__ . '/vod-config.php';

$api_dev_key = $config['pastebin']['api_key'];
$paste_file  = $config['pastebin']['vodlist_path'];
$paste_log   = $config['pastebin']['log_path'];

// Inhalt einlesen
$content = file_exists($paste_file) ? file_get_contents($paste_file) : '';

if (empty($content)) {
    echo "❌ Keine Daten in vodlist.txt gefunden!";
    exit;
}

// Parameter vorbereiten
$api_paste_code        = urlencode($content);
$api_paste_private     = '1'; // unlisted
$api_paste_name        = urlencode('vodlist.txt');
$api_paste_expire_date = '1W';
$api_paste_format      = 'text';
$api_user_key          = ''; // leer für Guest-Paste

$post_data = 
    'api_option=paste' .
    '&api_user_key=' . $api_user_key .
    '&api_paste_private=' . $api_paste_private .
    '&api_paste_name=' . $api_paste_name .
    '&api_paste_expire_date=' . $api_paste_expire_date .
    '&api_paste_format=' . $api_paste_format .
    '&api_dev_key=' . $api_dev_key .
    '&api_paste_code=' . $api_paste_code;

// CURL
$ch = curl_init('https://pastebin.com/api/api_post.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Ergebnis
echo "<pre>";
echo "HTTP-Code: $httpCode\n";
if (strpos($response, 'pastebin.com') !== false) {
$raw_url = str_replace('https://pastebin.com/', 'pastebin.com/raw/', $response);
file_put_contents($paste_log, $raw_url);
echo "✅ Upload erfolgreich!\n🔗 Raw-URL: $raw_url\n";
} else {
    echo "❌ Fehler beim Upload zu Pastebin:\n$response\n";
}
echo "</pre>";
