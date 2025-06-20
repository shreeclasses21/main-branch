<?php
session_start();
header('Content-Type: application/json');

echo json_encode([
    ['value' => 'MH', 'label' => 'Maharashtra'],
    ['value' => 'GJ', 'label' => 'Gujarat'],
    ['value' => 'UP', 'label' => 'Uttar Pradesh']
]);
