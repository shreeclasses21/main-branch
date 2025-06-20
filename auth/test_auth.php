<?php
include 'oauth.php';

try {
    $token = getSalesforceToken();
    echo "Access Token: " . $token['access_token'] . "<br>";
    echo "Instance URL: " . $token['instance_url'];
} catch (Exception $e) {
    echo "Auth failed: " . $e->getMessage();
}
