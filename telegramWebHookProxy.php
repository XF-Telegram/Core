<?php

$requestMethod = strtolower(!empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
if ($requestMethod != 'post')
{
    exit();
}

$contentType = strtolower(!empty($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'not-detected');
if (strncmp($contentType, 'application/json', 16) != 0)
{
    exit();
}

$webHookUrl = $_GET['_target'];
$data = file_get_contents('php://input');

if (!extension_loaded('curl'))
{
    exit();
}

// https://stackoverflow.com/questions/11079135/how-to-post-json-data-with-php-curl
$ch = curl_init($webHookUrl);
curl_setopt_array($ch, [
    CURLOPT_POSTFIELDS      => $data,
    CURLOPT_HTTPHEADER      => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER  => true,
	CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0',
]);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Proxify response.
http_response_code($httpCode);
echo($result);
