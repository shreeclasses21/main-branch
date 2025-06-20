<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// 🔒 Require JSON body
$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing JSON body']);
    exit;
}

$data = json_decode($raw, true);
if (!$data || !isset($_SESSION['contact_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input or not logged in']);
    exit;
}

$contactId = $_SESSION['contact_id'];

try {
    // ✅ Update student profile and lock further edits
    $stmt = $pdo->prepare("
        UPDATE students SET
            phone             = :phone,
            mobile            = :mobile,
            date_of_birth     = :dob,
            guardian_name     = :guardianName,
            profile_photo_url = :photoUrl,
            board             = :board,
            section           = :section,
            current_grade     = :grade,
            mailing_street    = :mailingStreet,
            mailing_city      = :mailingCity,
            mailing_state     = :mailingState,
            mailing_postal    = :mailingPostal,
            mailing_country   = :mailingCountry,
            updated_at        = NOW(),
            first_profile     = 1
        WHERE id = :id
    ");

    $stmt->execute([
        'phone'           => $data['phone'] ?? null,
        'mobile'          => $data['mobile'] ?? null,
        'dob'             => $data['dob'] ?? null,
        'guardianName'    => $data['guardianName'] ?? null,
        'photoUrl'        => $data['photoUrl'] ?? null,
        'board'           => $data['board'] ?? null,
        'section'         => $data['section'] ?? null,
        'grade'           => $data['grade'] ?? null,
        'mailingStreet'   => $data['mailingStreet'] ?? null,
        'mailingCity'     => $data['mailingCity'] ?? null,
        'mailingState'    => $data['mailingState'] ?? null,
        'mailingPostal'   => $data['mailingPostal'] ?? null,
        'mailingCountry'  => $data['mailingCountry'] ?? null,
        'id'              => $contactId
    ]);

    echo json_encode(['success' => true, 'message' => '✅ Student profile updated and locked for further edits.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB Error: ' . $e->getMessage()]);
}
