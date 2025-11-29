<?php
session_start();
require_once 'db.php';

// Fetch all logs with student details and medicine name, excluding completed visits
$logs = [];
$result = $conn->query("SELECT sv.*, s.student_id as student_id_string, CONCAT(s.first_name, ' ', s.last_name) as student_name, s.course, m.name as medicine_name FROM student_visits sv LEFT JOIN students s ON sv.student_id = s.id LEFT JOIN medicines m ON sv.med_id = m.name WHERE sv.status != 'Completed' ORDER BY sv.id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Visit Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleMenu() {
            document.getElementById('menu').classList.toggle('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openModal(id = null) {
            const modalTitle = document.getElementById('modalTitle');
            const modalBox = document.getElementById('modalBox');
            const submitBtn = document.getElementById('submit_btn');
            const formAction = document.getElementById('form_action');
            const footer = document.getElementById('modalFooter');

            if (id) {
                // ---- Editing Mode ----
                const row = document.getElementById(`row-${id}`);
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_student_id').value = row.dataset.studentId;
                document.getElementById('edit_student_name').value = row.dataset.studentName;
                document.getElementById('edit_course_year_section').value = row.dataset.course;
                document.getElementById('edit_reason').value = row.dataset.reason;
                document.getElementById('edit_action_taken').value = row.dataset.actionTaken;
                document.getElementById('edit_medicine_name').value = row.dataset.medicineName;
                document.getElementById('edit_dosage').value = row.dataset.dosage;
                document.getElementById('edit_quantity').value = row.dataset.quantity;
                document.getElementById('edit_visit_date').value = row.dataset.visitDate;


                formAction.value = "update";
                submitBtn.textContent = "Update";
                modalTitle.textContent = "Edit Student Visit Log";

                // Colors for edit mode
                modalBox.classList.remove("bg-orange-500");
                modalBox.classList.add("bg-white");
                footer.classList.remove("from-orange-500");
                footer.classList.add("from-white");
                modalTitle.classList.remove("text-white");
                modalTitle.classList.add("text-black");

                submitBtn.className = "px-4 py-2 bg-blue-600 text-white rounded";
            } else {
                // ---- Adding Mode ----
                document.getElementById('edit_id').value = "";
                document.getElementById('edit_student_id').value = "";
                document.getElementById('edit_student_name').value = "";
                document.getElementById('edit_course_year_section').value = "";
                document.getElementById('edit_reason').value = "";
                document.getElementById('edit_action_taken').value = "";
                document.getElementById('edit_medicine_name').value = "";
                document.getElementById('edit_dosage').value = "";
                document.getElementById('edit_quantity').value = "";
                document.getElementById('edit_visit_date').value = new Date().toISOString().slice(0,16);


                formAction.value = "add";
                submitBtn.textContent = "Add";
                modalTitle.textContent = "Add New Student Visit Log";

                // Colors for add mode
                modalBox.classList.remove("bg-white");
                modalBox.classList.add("bg-orange-500");
                footer.classList.remove("from-white");
                footer.classList.add("from-orange-500");
                modalTitle.classList.remove("text-black");
                modalTitle.classList.add("text-white");

                submitBtn.className = "px-4 py-2 bg-white text-orange-600 font-bold rounded";
            }

            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</head>
<body class="bg-[#BB1D1D]">

<!-- Header -->
<header class="bg-gradient-to-r from-orange-400 to-red-700 text-white p-4 flex justify-between items-center relative">
    <button onclick="toggleMenu()" class="text-2xl">&#9776;</button>
    <div class="absolute right-4 -top-2">
        <img src="images/logo.png" alt="Cordova Logo" class="w-31 h-32">
    </div>
</header>

<!-- Nurse Profile -->
<section class="bg-white shadow-md p-4 rounded-md max-w-6xl mx-auto mt-4 flex items-center gap-4">
    <img src="images/Nurse.jpg" alt="Nurse" class="w-20 h-20 rounded-full border-4 border-green-500">
    <div>
        <h2 class="text-xl font-bold">Mrs. Lorefe F. Verallo</h2>
        <p class="text-sm text-gray-600">Nurse ID:</p>
    </div>
</section>

<!-- Dropdown Menu -->
<div id="menu" class="absolute bg-white w-64 p-4 hidden top-[65px] left-0 shadow z-10 rounded-md">
    <h2 class="text-lg mb-4 font-semibold">File Clinic Explorer</h2>
    <hr class="mb-2">
    <a class="block mb-4" href="nurse.php">↩️ Go Back</a>

      <details class="group mb-4">
        <summary class="cursor-pointer flex items-center hover:bg-gray-300 transition p-2 rounded">
          📁 Documents
        </summary>
        <ul class="ml-6 mt-2 space-y-1 text-[15px]">
          <li><a href="physical_assessment.php" class="block hover:bg-gray-200 rounded px-1">↳ Student Physical Assessment Form</a></li>
          <li><a href="Health Service_report.php" class="block hover:bg-gray-200 rounded px-1">↳ Health Service Utilization Report</a></li>
          <li><a href="first_aid.php" class="block hover:bg-gray-200 rounded px-1">↳ First Aid Procedure</a></li>
          <li><a href="Emergency Plan.php" class="block hover:bg-gray-200 rounded px-1">↳ Emergency Respond Plan</a></li>
        </ul>
      </details>

    <a class="block mb-4" href="medication_dashboard.php">📁 Medical Supplies</a>
    <a class="block mb-4" href="studentfile_dashboard.php">📁 Student File Dashboard</a>
    <a class="block mb-4" href="registration.php">📁 Register Student Health</a>
    <a class="block mb-4" href="Student_visitlogs.php">📁 Student Visit Logs</a>
    <a class="block mb-4" href="appointment_history.php">📁 Appointment History</a>
    <a class="block mb-4" href="emergency_reports.php">📁 Emergency Reports</a>
    <a class="block mb-4" href="responder_status.php">📁 Responder Status</a>
</div>


<!-- Main Content -->
<main class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-center mb-4">Student Visit Logs</h2>

    <!-- Add Button -->
    <div class="flex justify-end mb-4">
        <button onclick="openModal()" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">+ Add</button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-blue-200">
                <tr>
                    <th class="p-2 border">Student ID</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Course YR&Sec</th>
                    <th class="p-2 border">Reason</th>
                    <th class="p-2 border">Action taken</th>
                    <th class="p-2 border">Medicine Name</th>
                    <th class="p-2 border">Dosage</th>
                    <th class="p-2 border">Quantity</th>
                    <th class="p-2 border">Date/Time</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($logs) > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr id="row-<?= $log['id'] ?>" 
                            data-student-id="<?= htmlspecialchars($log['student_id_string']) ?>"
                            data-student-name="<?= htmlspecialchars($log['student_name']) ?>"
                            data-course="<?= htmlspecialchars($log['course']) ?>"
                            data-reason="<?= htmlspecialchars($log['reason']) ?>"
                            data-action-taken="<?= htmlspecialchars($log['action_taken']) ?>"
                            data-med-id="<?= htmlspecialchars($log['med_id']) ?>"
                            data-medicine-name="<?= htmlspecialchars($log['medicine_name'] ?? $log['med_id']) ?>"
                            data-dosage="<?= htmlspecialchars($log['dosage']) ?>"
                            data-quantity="<?= htmlspecialchars($log['quantity']) ?>"
                            data-visit-date="<?= date('Y-m-d\TH:i', strtotime($log['visit_date'])) ?>"
                        >
                            <td class="p-2 border"><?= htmlspecialchars($log['student_id_string']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['student_name']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['course']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['reason']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['action_taken']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['medicine_name'] ?? $log['med_id']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['dosage']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($log['quantity']) ?></td>
                            <td class="p-2 border"><?= date('m/d/Y h:i A', strtotime($log['visit_date'])) ?></td>
                            <td class="p-2 border text-center">
                                <button onclick="openModal(<?= $log['id'] ?>)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 mr-1">Edit</button>
                                <form action="mark_visit_done.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="visit_id" value="<?= $log['id'] ?>">
                                    <button type="submit" class="bg-orange-500 text-white px-2 py-1 rounded hover:bg-orange-600">Done</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center p-4 text-gray-500">No visit logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


</main>

<!-- Add/Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
  <div id="modalBox" 
       class="bg-white p-6 rounded-xl w-[90%] max-w-lg max-h-[85vh] overflow-y-auto shadow-xl">
       
    <h3 class="text-lg font-bold mb-4 text-black" id="modalTitle">Edit Student Visit Log</h3>
    
    <form action="update.php" method="POST" class="space-y-3">
      <input type="hidden" name="id" id="edit_id">
      <input type="hidden" name="action" id="form_action" value="update">

      <label class="block">Student ID
        <input type="text" name="student_id" id="edit_student_id" class="w-full border p-2 rounded bg-white" required>
      </label>
      <label class="block">Student Name
        <input type="text" name="student_name" id="edit_student_name" class="w-full border p-2 rounded bg-white" required>
      </label>
      <label class="block">Course YR&Sec
        <input type="text" name="course" id="edit_course_year_section" class="w-full border p-2 rounded bg-white" required>
      </label>
      <label class="block">Reason
        <input type="text" name="reason" id="edit_reason" class="w-full border p-2 rounded bg-white" required>
      </label>
      <label class="block">Action taken
        <input type="text" name="action_taken" id="edit_action_taken" class="w-full border p-2 rounded bg-white" required>
      </label>
      <label class="block">Medicine Name
        <input type="text" name="medicine_name" id="edit_medicine_name" class="w-full border p-2 rounded bg-white">
      </label>
      <label class="block">Dosage
        <input type="text" name="dosage" id="edit_dosage" class="w-full border p-2 rounded bg-white">
      </label>
      <label class="block">Quantity
        <input type="text" name="quantity" id="edit_quantity" class="w-full border p-2 rounded bg-white">
      </label>
      <label class="block">Date/Time of Visit
        <input type="datetime-local" name="visit_date" id="edit_visit_date" class="w-full border p-2 rounded bg-white" required>
      </label>


      <!-- Sticky Buttons -->
     <div id="modalFooter" class="flex justify-end gap-3 mt-4 p-2">
        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
        <button type="submit" id="submit_btn" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
      </div>
    </form>
  </div>
</div>


</body>
</html>
<?php if ($conn) $conn->close(); ?>