<?php
session_start();
header('Content-Type: application/json');

try {
    // Only GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        exit;
    }

    // Static values (replace or load from DB if needed)
    $response = [
        'Board__c' => ['CBSE', 'ICSE', 'SSC', 'HSC'],
        'Section__c' => ['English', 'Semi - English', 'Marathi']
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
