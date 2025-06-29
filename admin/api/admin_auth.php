<?php
session_start();
require_once __DIR__ . '/../../config/db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Hash the entered password using SHA-256
$hashedInputPassword = hash('sha256', $password);

try {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $hashedInputPassword === $admin['password']) {
        // ✅ Auth success
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: ../dashboard.php');
        exit;
    } else {
        // ❌ Wrong credentials
        $_SESSION['admin_error'] = "Invalid username or password.";
        header('Location: ../login.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['admin_error'] = "Database error: " . $e->getMessage();
    header('Location: ../login.php');
    exit;
}
