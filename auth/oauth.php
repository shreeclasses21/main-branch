<?php

function getSalesforceToken() {
    $config = include(__DIR__ . '/../config/config.php');

    $params = [
        'grant_type'    => 'password',
        'client_id'     => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'username'      => $config['username'],
        'password'      => $config['password'], // password + token
    ];

    $ch = curl_init($config['login_url'] . '/services/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (!isset($data['access_token'])) {
        // Optional: log to file
        file_put_contents(__DIR__ . '/../logs/debug.log', date('c') . ' - OAuth Error: ' . $response . PHP_EOL, FILE_APPEND);
        throw new Exception("OAuth failed: " . $response);
    }

    return [
        'access_token' => $data['access_token'],
        'instance_url' => $data['instance_url']
    ];
}
