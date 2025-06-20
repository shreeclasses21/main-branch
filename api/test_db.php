<?php
require_once __DIR__ . '/../config/db.php';  // Adjust path if needed

try {
    // Use global $pdo (from db.php)
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "✅ DB Connection Successful! Connected to: <strong>{$result['db_name']}</strong>";
} catch (PDOException $e) {
    echo "❌ DB Connection Failed: " . $e->getMessage();
}
