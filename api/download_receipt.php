<?php
session_start();
header('Content-Type: text/html');

// ✅ Check session
if (!isset($_SESSION['contact_id'])) {
    echo "❌ Unauthorized access";
    exit;
}

require_once __DIR__ . '/../config/db.php';

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Invalid invoice ID.";
    exit;
}

$invoiceId = $_GET['id'];

try {
    // ✅ Fetch invoice directly by ID (assuming only visible to correct student)
    $stmt = $pdo->prepare("SELECT * FROM payment_invoices WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo "❌ No invoice found for this ID.";
        exit;
    }

    // ✅ Output a basic receipt
    echo "<h2>✅ Payment Receipt</h2>";
    echo "<p><strong>Student:</strong> {$invoice['student_name']}</p>";
    echo "<p><strong>Amount:</strong> ₹{$invoice['amount']}</p>";
    echo "<p><strong>Month:</strong> {$invoice['month']} / {$invoice['year']}</p>";
    echo "<p><strong>Status:</strong> {$invoice['status']}</p>";
    echo "<p><strong>Type:</strong> {$invoice['type']}</p>";
    echo "<p><strong>Generated On:</strong> {$invoice['created_at']}</p>";

} catch (PDOException $e) {
    echo "❌ Error fetching invoice: " . $e->getMessage();
}
