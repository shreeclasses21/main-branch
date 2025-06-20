<?php
require_once __DIR__ . '/../config/db.php';

$username = 'admin';
$password = password_hash('Admin@123', PASSWORD_BCRYPT);
$name = 'Site Admin';

$stmt = $pdo->prepare("INSERT INTO admin_users (username, password, name) VALUES (?, ?, ?)");
$stmt->execute([$username, $password, $name]);

echo "Admin user created.";
