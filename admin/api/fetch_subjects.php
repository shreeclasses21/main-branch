<?php
require_once __DIR__ . '/../../config/db.php';
$stmt = $pdo->query("SELECT * FROM subjects ORDER BY grade, subject_name");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
