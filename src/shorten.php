<?php
// Reference copy of the Run Script inside "Shorten URL with YOURLS.alfredworkflow".
// Alfred substitutes {query} before running it; DOMAIN, SIGNATURE and CLIPBOARD arrive as environment variables.

// config comes from the workflow's Configure Workflow settings
$yourls_domain = getenv('DOMAIN');    // your YOURLS domain, e.g. your.ls (no http/https)
$signature     = getenv('SIGNATURE'); // secure signature token for passwordless API calls

if ($yourls_domain == '' || $signature == '') die('Yourls Workflow is not configured');

$typed = trim('{query}');
$clip  = trim((string) getenv('CLIPBOARD'));

if (strpos($typed, '*') !== false) {
    // "url*keyword" or "*keyword"
    list($url, $keyword) = array_map('trim', explode('*', $typed, 2));
    if ($url === '') $url = $clip;
} elseif ($typed !== '' && !preg_match('#\.|://#', $typed)) {
    // a bare word like "test" = keyword for the clipboard URL
    $url = $clip;
    $keyword = $typed;
} else {
    // a typed URL, or nothing at all
    $url = $typed !== '' ? $typed : $clip;
    $keyword = '';
}

if ($url === '') die('Nothing to shorten');
if (!preg_match('#^https?://#i', $url)) $url = 'https://' . $url;

$api = 'https://' . $yourls_domain . '/yourls-api.php?' . http_build_query([
    'signature' => $signature,
    'action'    => 'shorturl',
    'keyword'   => $keyword,
    'url'       => $url,
    'format'    => 'json',
]);

// ignore_errors lets us read YOURLS's own error message instead of a PHP warning
$ctx      = stream_context_create(['http' => ['ignore_errors' => true]]);
$response = file_get_contents($api, false, $ctx);
$json     = json_decode($response, true);

if (!empty($json['shorturl'])) die($json['shorturl']);
die('YOURLS error: ' . ($json['message'] ?? 'no response from server'));
