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

// Erster Upload-Versuch (ohne Debug)
$ch = curl_init('https://pastebin.com/api/api_post.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<pre>";
echo "HTTP-Code: $httpCode\n";
if (strpos($response, 'pastebin.com') !== false) {
    $raw_url = str_replace('https://pastebin.com/', 'pastebin.com/raw/', $response);
    file_put_contents($paste_log, $raw_url);
    echo "✅ Upload erfolgreich!\n🔗 Raw-URL: $raw_url\n";
    echo "</pre>";
    exit; // fertig, kein Debug nötig
} else {
    echo "❌ Fehler beim Upload zu Pastebin:\n$response\n";
    echo "</pre>";
}

// Zweiter Versuch mit Debug, nur weil der erste fehlgeschlagen ist
$ch = curl_init('https://pastebin.com/api/api_post.php');
$verbose = fopen('php://temp', 'w+');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $post_data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT      => 'curl/7 Pastebin Client',
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
    CURLOPT_VERBOSE        => true,
    CURLOPT_STDERR         => $verbose
]);

$response = curl_exec($ch);
$errNo = curl_errno($ch);
$err   = curl_error($ch);
$http  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

rewind($verbose);
$debug = stream_get_contents($verbose);

echo "<pre>HTTP-Code: $http\ncURL-ErrorNo: $errNo\ncURL-Error: $err\nAntwort:\n$response\n\nDEBUG:\n$debug\n</pre>";
