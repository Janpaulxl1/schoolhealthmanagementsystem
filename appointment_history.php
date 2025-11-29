<?php
require 'db.php';

$student_id_param = $_GET['student_id'] ?? '';

if ($student_id_param) {
    $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, student_id FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id_param);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if (!$student) {
        die("Student not found.");
    }

    $student_db_id = $student['id'];

    $stmt2 = $conn->prepare("
      SELECT al.date, al.time, al.reason, al.status, al.student_name,
             am.medicine_name, am.dosage, am.quantity, am.action_taken
      FROM appointment_logs al
      LEFT JOIN appointments a ON al.appointment_id = a.id
      LEFT JOIN appointment_medications am ON a.id = am.appointment_id
      WHERE al.student_name LIKE CONCAT('%', (SELECT CONCAT(first_name, ' ', last_name) FROM students WHERE id = ?), '%')
      ORDER BY al.date DESC, al.time DESC, am.created_at DESC
    ");
    $stmt2->bind_param("i", $student_db_id);
    $stmt2->execute();
    $result = $stmt2->get_result();
} else {
    // Show all appointment logs if no student_id provided
    $stmt2 = $conn->prepare("
      SELECT al.date, al.time, al.reason, al.status, al.student_name,
             am.medicine_name, am.dosage, am.quantity, am.action_taken
      FROM appointment_logs al
      LEFT JOIN appointments a ON al.appointment_id = a.id
      LEFT JOIN appointment_medications am ON a.id = am.appointment_id
      ORDER BY al.date DESC, al.time DESC, am.created_at DESC
    ");
    $stmt2->execute();
    $result = $stmt2->get_result();
    $student = null; // No specific student
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment History</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background-color: #CBC8C8; }
  </style>
</head>
<body class="p-6">
  <div class="mb-4">
    <a href="javascript:history.back()"
       class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">⬅ Back</a>
  </div>

  <h1 class="text-2xl font-bold mb-2">Appointment History</h1>
  <?php if ($student): ?>
  <p class="mb-4 text-gray-700">
    Student: <span class="font-semibold">
      <?= htmlspecialchars($student['first_name'] . " " . $student['middle_name'] . " " . $student['last_name']) ?>
    </span> (<?= htmlspecialchars($student['student_id']) ?>)
  </p>
  <?php endif; ?>

  <div class="bg-white rounded-xl shadow p-4">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2 border">Date</th>
          <th class="p-2 border">Time</th>
          <?php if (!$student): ?>
          <th class="p-2 border">Student Name</th>
          <?php endif; ?>
          <th class="p-2 border">Reason</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Medicine Name</th>
          <th class="p-2 border">Dosage</th>
          <th class="p-2 border">Quantity</th>
          <th class="p-2 border">Action Taken</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="p-2 border"><?= htmlspecialchars($row['date']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['time']) ?></td>
              <?php if (!$student): ?>
              <td class="p-2 border"><?= htmlspecialchars($row['student_name']) ?></td>
              <?php endif; ?>
              <td class="p-2 border"><?= htmlspecialchars($row['reason']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['status']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['medicine_name'] ?? 'N/A') ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['dosage'] ?? 'N/A') ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['quantity'] ?? 'N/A') ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['action_taken'] ?? 'N/A') ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="<?= $student ? 9 : 10 ?>" class="p-4 text-center">No appointments found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
