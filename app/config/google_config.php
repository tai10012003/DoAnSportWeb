<?php
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../../.env');

$client_id = getenv('GOOGLE_CLIENT_ID');
$client_secret = getenv('GOOGLE_CLIENT_SECRET');

if (!$client_id || !$client_secret) {
    error_log('Google credentials not found in environment variables');
}

define('GOOGLE_CLIENT_ID', $client_id);
define('GOOGLE_CLIENT_SECRET', $client_secret);
define('GOOGLE_REDIRECT_URI', 'http://localhost/WebbandoTT/google-callback.php');

require_once __DIR__ . '/../../vendor/autoload.php';

function getGoogleClient() {
    $client = new Google\Client();
    $client->setApplicationName('Sport Elite');
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
    $client->addScope('https://www.googleapis.com/auth/userinfo.email');
    return $client;
}

