<?php
require_once __DIR__ . '/../../config/db.php';

// Get today's month and year
$monthName = date('F'); // e.g. "June"
$yearNum = date('Y');

// Step 1: Fetch all students with student_id
$studentsStmt = $pdo->query("SELECT id FROM students WHERE student_id IS NOT NULL");
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Step 2: Fetch all leave types
$leaveTypesStmt = $pdo->query("SELECT id, allowed_per_month FROM leave_types");
$leaveTypes = $leaveTypesStmt->fetchAll(PDO::FETCH_ASSOC);

// Step 3: Prepare INSERT statement
$insertStmt = $pdo->prepare("
    INSERT INTO leave_assignments (student_id, leave_type_id, month, year, allowed, used)
    VALUES (:student_id, :leave_type_id, :month, :year, :allowed, 0)
");

// Step 4: Assign only if NOT already assigned for this month
$checkStmt = $pdo->prepare("
    SELECT COUNT(*) FROM leave_assignments 
    WHERE student_id = :student_id AND month = :month AND year = :year
");

$count = 0;
foreach ($students as $student) {
    $checkStmt->execute([
        'student_id' => $student['id'],
        'month'      => $monthName,
        'year'       => $yearNum
    ]);
    $alreadyAssigned = $checkStmt->fetchColumn();

    if ($alreadyAssigned == 0) {
        // Assign all leave types to this student
        foreach ($leaveTypes as $type) {
            $insertStmt->execute([
                'student_id'    => $student['id'],
                'leave_type_id' => $type['id'],
                'month'         => $monthName,
                'year'          => $yearNum,
                'allowed'       => $type['allowed_per_month']
            ]);
            $count++;
        }
    }
}

echo "✅ $count leave assignments inserted for month: $monthName $yearNum";
