<?php
require 'db.php';

$section_id = $_GET['section_id'] ?? 0;

// If request is for JSON (API call)
if (isset($_GET['json'])) {
    header('Content-Type: application/json');

    $stmt = $conn->prepare("SELECT * FROM students WHERE section_id = ?");
    $stmt->bind_param("i", $section_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode(["students" => $students]);
    exit;
}

// Otherwise, display HTML table
$stmt = $conn->prepare("SELECT * FROM students WHERE section_id = ?");
$stmt->bind_param("i", $section_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-4">Student List (Section <?= htmlspecialchars($section_id) ?>)</h1>

  <div class="bg-white rounded-xl shadow p-4">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2 border">Student ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Phone</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Emergency Contact</th>
          <th class="p-2 border">Requirements</th>
          <th class="p-2 border">Actions</th> 
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="p-2 border"><?= htmlspecialchars($row['student_id']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['email']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($row['emergency_contact'] ?? '-') ?></td>
              <td class="p-2 border"><?= $row['requirements_completed'] == 1 ? 'Yes' : 'No' ?></td>
              <td class="p-2 border">
                <a href="appointment_history.php?student_id=<?= urlencode($row['id']) ?>"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                   View Appointments
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="p-4 text-center">No students found in this section.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
