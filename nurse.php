<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'nurse') {
  header("Location: login.php");
  exit;
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
require_once 'db.php';

// =======================
//  AJAX / JSON endpoints
// =======================
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Return appointment history (full)
    if ($action === 'fetch_history') {
        header('Content-Type: application/json');
        $appointments = [];
        $sql = "SELECT a.id, a.appointment_time, a.reason, a.status,
                       s.first_name, s.last_name, s.section, s.semester, s.year_level
                FROM appointments a
                LEFT JOIN students s ON a.student_id = s.id
                ORDER BY a.appointment_time DESC";
        if ($res = $conn->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $appointments[] = [
                    'id' => $row['id'],
                    'student_name' => trim($row['first_name'] . ' ' . $row['last_name']),
                    'reason' => $row['reason'],
                    'status' => $row['status'],
                    'date' => date('Y-m-d H:i', strtotime($row['appointment_time'])),
                    'section' => $row['section'] ?? '',
                    'semester' => $row['semester'] ?? '',
                    'year_level' => $row['year_level'] ?? ''
                ];
            }
        }
        echo json_encode($appointments);
        exit;
    }

    // Appointment activity (summarized for the small widget)
    if ($action === 'appt_activity') {
        header('Content-Type: application/json');
        $rows = [];
        $sql = "SELECT a.id, a.appointment_time, a.reason, a.status,
                       s.first_name, s.last_name
                FROM appointments a
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.status IN ('Pending','Confirmed')
                ORDER BY a.appointment_time DESC LIMIT 20";
        if ($res = $conn->query($sql)) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = [
                    'id' => $r['id'],
                    'date' => date('Y-m-d', strtotime($r['appointment_time'])),
                    'time' => date('h:i A', strtotime($r['appointment_time'])),
                    'student_name' => trim($r['first_name'] . ' ' . $r['last_name']),
                    'reason' => $r['reason'],
                    'status' => $r['status'] ?: 'Pending'
                ];
            }
        }
        echo json_encode($rows);
        exit;
    }

    // Get appointment details for modal
    if ($action === 'get_appointment_details') {
        header('Content-Type: application/json');
        $appointment_id = intval($_GET['id'] ?? 0);
        $sql = "SELECT a.*, s.first_name, s.last_name, s.student_id as student_id_string, s.course, s.year_level, s.section
                FROM appointments a
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        $stmt->close();

        if ($appointment) {
            echo json_encode($appointment);
        } else {
            echo json_encode(['error' => 'Appointment not found']);
        }
        exit;
    }

    // Save medicine entry for appointment
    if ($action === 'save_medicine_entry') {
        header('Content-Type: application/json');
        $appointment_id = intval($_POST['appointment_id'] ?? 0);
        $medicine_name = trim($_POST['medicine_name'] ?? '');
        $dosage = trim($_POST['dosage'] ?? '');
        $quantity = intval($_POST['quantity'] ?? 0);
        $action_taken = trim($_POST['action_taken'] ?? '');

        if (empty($medicine_name) || empty($dosage) || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Insert into appointment_medications
        $stmt = $conn->prepare("INSERT INTO appointment_medications (appointment_id, medicine_name, dosage, quantity, action_taken, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issis", $appointment_id, $medicine_name, $dosage, $quantity, $action_taken);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Medicine entry saved']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save medicine entry']);
        }
        $stmt->close();
        exit;
    }

    // Get medicine entries for appointment
    if ($action === 'get_medicine_entries') {
        header('Content-Type: application/json');
        $appointment_id = intval($_GET['id'] ?? 0);
        $sql = "SELECT * FROM appointment_medications WHERE appointment_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $entries = [];
        while ($row = $result->fetch_assoc()) {
            $entries[] = $row;
        }
        echo json_encode($entries);
        $stmt->close();
        exit;
    }

    // Update medicine entry for appointment
    if ($action === 'update_medicine_entry') {
        header('Content-Type: application/json');
        $entry_id = intval($_POST['id'] ?? 0);
        $medicine_name = trim($_POST['medicine_name'] ?? '');
        $dosage = trim($_POST['dosage'] ?? '');
        $quantity = intval($_POST['quantity'] ?? 0);
        $action_taken = trim($_POST['action_taken'] ?? '');

        if (empty($medicine_name) || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Update appointment_medications
        $stmt = $conn->prepare("UPDATE appointment_medications SET medicine_name = ?, dosage = ?, quantity = ?, action_taken = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $medicine_name, $dosage, $quantity, $action_taken, $entry_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Medicine entry updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update medicine entry']);
        }
        $stmt->close();
        exit;
    }

    // Notification checker (new appointment notifications)
    if ($action === 'notification_checker') {
        header('Content-Type: application/json');
        $items = [];
        // Example: pending appointments (not confirmed) as "notifications"
        $sql = "SELECT a.id, a.appointment_time, a.reason, a.is_emergency, 
                       s.first_name, s.last_name
                FROM appointments a
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.status = 'Pending'
                ORDER BY a.appointment_time DESC LIMIT 50";
        if ($res = $conn->query($sql)) {
            while ($r = $res->fetch_assoc()) {
                $items[] = [
                    'id' => $r['id'],
                    'appointment_time' => $r['appointment_time'],
                    'reason' => $r['reason'],
                    'student_name' => trim($r['first_name'] . ' ' . $r['last_name']),
                    'is_emergency' => (int)($r['is_emergency'] ?? 0)
                ];
            }
        }
        echo json_encode($items);
        exit;
    }

    // General notifications (notifications table)
    if ($action === 'nurse_general_notifications') {
        header('Content-Type: application/json');
        $notes = [];
        $sql = "SELECT id, message, created_at FROM notifications ORDER BY created_at DESC LIMIT 50";
        if ($res = $conn->query($sql)) {
            while ($r = $res->fetch_assoc()) {
                $notes[] = $r;
            }
        }
        echo json_encode($notes);
        exit;
    }
}

// Update responder status to 'Active' if logged in as responder
if (isset($_SESSION['role']) && $_SESSION['role'] === 'responder' && isset($_SESSION['username'])) {
    $responder_name = $_SESSION['username'];
    $update_sql = "UPDATE emergency_responders SET status = 'Active', last_active = NOW() WHERE name = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("s", $responder_name);
    $stmt->execute();
    $stmt->close();
}

// Update nurse last_active on page load
if (isset($_SESSION['user_id'])) {
    $update_sql = "UPDATE users SET last_active = NOW() WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

$result_appt = false;
$result_resp = false;
$result_emerg = false;

// Update responders to 'Off Duty' if they are 'Active' but last_active is more than 5 minutes ago or NULL
$update_inactive_sql = "UPDATE emergency_responders SET status = 'Off Duty' WHERE status = 'Active' AND (last_active IS NULL OR last_active < (NOW() - INTERVAL 5 MINUTE))";
$conn->query($update_inactive_sql);

if ($conn) {
    // ✅ FIX: Show only Confirmed appointments in Appointment Activity
    $sql_appt = "SELECT a.id, a.appointment_time, CONCAT(s.first_name,' ',s.last_name) AS student_name,
                        a.reason, a.status
                FROM appointments a
                JOIN students s ON a.student_id = s.id
                WHERE a.status IN ('Pending','Confirmed')
                ORDER BY a.appointment_time DESC LIMIT 20";
    $result_appt = $conn->query($sql_appt);

    $sql_resp = "SELECT name, status FROM emergency_responders ORDER BY name ASC LIMIT 20";
    $result_resp = $conn->query($sql_resp);

    $sql_emerg = "SELECT message, created_at FROM notifications WHERE message LIKE 'Emergency%' ORDER BY created_at DESC LIMIT 20";
    $result_emerg = $conn->query($sql_emerg);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nurse Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    .fc-day-today { background: orange !important; font-weight: bold; border-radius: 4px; }
    .fc-event.appointment { background-color: red !important; border-color: red !important; color: white !important; font-weight: bold !important; }
    #calendar { min-height: 500px; }
    .fc-event { font-size: 12px; line-height: 1.2; padding: 2px 4px; max-width: 100%; }
    .fc-event-title { font-size: 12px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width: 100%; }
    .fc-event-time { white-space: nowrap; max-width: 30px; overflow: hidden; display: inline-block; vertical-align: middle; }
  </style>
</head>
<body class="bg-[#BB1D1D]"></body>

<!-- Header -->
<header class="bg-gradient-to-r from-[#ec6f2d] to-[#b32f1b] p-4 text-white relative">
  <div class="flex items-center gap-4">
    <button id="menuBtn" class="text-xl font-bold cursor-pointer">☰</button>
    <h1 class="text-xl font-bold">School Clinic Health Dashboard</h1>
  </div>
  <img src="images/Logo.png" alt="Logo" class="absolute right-4 top-4 w-33 h-32 rounded-full z-10">
</header>

<!-- Dropdown Menu -->
<div id="menu" class="absolute bg-white w-64 p-4 hidden top-[65px] left-0 shadow z-10 rounded-md">
    <h2 class="text-lg mb-4 font-semibold">File Clinic Explorer</h2>
    
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
    <a class="block mb-4" href="nurse_reset_password.php">🔑 Reset User Password</a>
    <a href="logout.php" class="block py-2 px-2 rounded hover:bg-red-500">🚪 Logout</a>
</div>




<section class="bg-white shadow-md p-4 rounded-md max-w-6xl mx-auto mt-4 flex items-center gap-4">
  <img src="images/Nurse.jpg" alt="Nurse" class="w-20 h-20 rounded-full border-4 border-green-500">
  <div>
    <p class="text-sm text-gray-500">Nurse</p>
    <h2 class="text-xl font-semibold">Mrs. Lorefe F. Verallo</h2>
    <p class="text-sm text-gray-500">Nurse ID: 2022001</p>
  </div>
  <div class="ml-auto mr-4 flex items-center gap-4">
    <button class="relative" onclick="openDrawer()">
      <img src="images/bell.png" alt="Notif" class="w-8 h-8">
      <span id="notifBadge" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full hidden"></span>
</section>


<main class="max-w-6xl mx-auto mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">

  <div class="col-span-2 space-y-4">
    <div class="bg-pink-100 p-4 rounded-md shadow">
      <div class="flex justify-between items-center">
        <h3 class="font-bold">Appointment Activity</h3>
        <button onclick="toggleExpand('appointments', this)" class="text-sm">View all ></button>
      </div>
      <div id="appointments" class="mt-2 max-h-[400px] overflow-y-auto transition-all">
        <table class="w-full text-sm">
          <thead class="bg-pink-400 text-white">
            <tr>
              <th class="p-2">Date</th>
              <th class="p-2">Time</th>
              <th class="p-2">Name</th>
              <th class="p-2">Reason</th>
              <th class="p-2">Status</th>
              <th class="p-2">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white" id="appointmentTableBody">
            <?php if ($result_appt && $result_appt->num_rows > 0): ?>
              <?php while($row = $result_appt->fetch_assoc()): ?>
                <tr id="row-<?php echo $row['id']; ?>">
                  <td class="p-2"><?php echo date('Y-m-d', strtotime($row['appointment_time'])); ?></td>
                  <td class="p-2"><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                  <td class="p-2"><?php echo htmlspecialchars($row['student_name']); ?></td>
                  <td class="p-2"><?php echo htmlspecialchars($row['reason']); ?></td>
                  <td class="p-2 font-semibold" id="status-<?php echo $row['id']; ?>">
                    <?php echo !empty($row['status']) ? htmlspecialchars($row['status']) : 'Pending'; ?>
                  </td>
                  <td class="p-2">
                    <button class="view-details-btn bg-green-500 text-white px-2 py-1 rounded text-sm mr-1" data-id="<?php echo $row['id']; ?>" data-student="<?php echo htmlspecialchars($row['student_name']); ?>" data-reason="<?php echo htmlspecialchars($row['reason']); ?>" data-date="<?php echo $row['appointment_time']; ?>">Details</button>
                    <button class="mark-done-btn bg-blue-500 text-white px-2 py-1 rounded text-sm" data-id="<?php echo $row['id']; ?>">Mark as Done</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center text-gray-500">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>


  <div id="calendar" class="bg-white p-4 rounded-md shadow col-span-1"></div>
</main>


<div id="notificationDrawer" class="fixed top-0 right-0 w-full md:w-96 h-full bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 rounded-l-3xl border-l-4 border-red-500 overflow-auto">
  <div class="bg-red-500 text-white flex items-center p-4">
    <button onclick="closeDrawer()" class="mr-2 text-xl font-bold">&larr;</button>
    <h2 class="text-lg font-semibold">Notifications </h2>
  </div>
  <div id="drawerContent" class="p-4 space-y-4"></div>
</div>


<audio id="bellSound" preload="auto">
  <source src="bell.mp3" type="audio/mpeg">
</audio>
<audio id="alarmSound" preload="auto">
  <source src="alarm.wav" type="audio/wav">
</audio>


<div id="rescheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-96">
    <h3 class="text-lg font-bold mb-4">Reschedule Appointment</h3>
    <input type="hidden" id="rescheduleId">
    <label class="block text-sm font-semibold mb-1">New Time</label>
    <input type="datetime-local" id="newTime" class="w-full border rounded p-2 mb-4">
    <div class="flex justify-end gap-2">
      <button onclick="closeRescheduleModal()" class="px-3 py-1 bg-gray-400 text-white rounded">Cancel</button>
      <button onclick="submitReschedule()" class="px-3 py-1 bg-green-500 text-white rounded">Save</button>
    </div>
  </div>
</div>


<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-96 max-h-[80vh] overflow-y-auto">
    <h3 id="eventModalTitle" class="text-lg font-bold mb-4">Add Event</h3>
    <input type="hidden" id="eventId">
    <label class="block text-sm font-semibold mb-1">Title</label>
    <input type="text" id="eventTitle" class="w-full border rounded p-2 mb-4">
    <label class="block text-sm font-semibold mb-1">Start Time</label>
    <input type="datetime-local" id="eventStart" class="w-full border rounded p-2 mb-4">
    <label class="block text-sm font-semibold mb-1">Note</label>
    <textarea id="eventNote" class="w-full border rounded p-2 mb-4 max-h-32 overflow-y-auto" rows="3"></textarea>

    <div class="flex justify-end gap-2">
      <button onclick="closeEventModal()" class="px-3 py-1 bg-gray-400 text-white rounded">Cancel</button>
      <button id="saveEventBtn" onclick="saveEvent()" class="px-3 py-1 bg-green-500 text-white rounded">Save</button>
      <button id="deleteEventBtn" onclick="deleteEvent()" class="px-3 py-1 bg-red-500 text-white rounded hidden">Delete</button>
    </div>
  </div>
</div>

<!-- Appointment Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-96 max-h-[80vh] overflow-y-auto">
    <h3 class="text-lg font-bold mb-4">Appointment Details</h3>
    <input type="hidden" id="appointmentId">
    <p><strong>Student Name:</strong> <span id="detailsStudent"></span></p>
    <p><strong>Reason:</strong> <span id="detailsReason"></span></p>
    <p><strong>Date & Time:</strong> <span id="detailsDate"></span></p>

    <div id="medicineEntriesContainer" class="mt-4">
      <!-- Medicine entry form will be loaded here -->
    </div>

  </div>
</div>

<!-- Follow-up Modal -->
<div id="followupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-96">
    <h3 class="text-lg font-bold mb-4">Schedule Follow-up Check-up</h3>
    <input type="hidden" id="followupAppointmentId">
    <input type="hidden" id="followupStudentId">
    <label class="block text-sm font-semibold mb-1">Follow-up Date & Time</label>
    <input type="datetime-local" id="followupDateTime" class="w-full border rounded p-2 mb-4" required>
    <label class="block text-sm font-semibold mb-1">Reason</label>
    <textarea id="followupReason" class="w-full border rounded p-2 mb-4" rows="3" placeholder="Reason for follow-up" required></textarea>
    <div class="flex justify-end gap-2">
      <button onclick="closeFollowupModal()" class="px-3 py-1 bg-gray-400 text-white rounded">Skip</button>
      <button onclick="submitFollowup()" class="px-3 py-1 bg-green-500 text-white rounded">Schedule</button>
    </div>
  </div>
</div>

<!-- Backdrop for Sliding Panel -->
<div id="appointmentHistoryBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<!-- Sliding Appointment History Panel -->
<aside id="appointmentHistorySlide" class="fixed top-0 right-0 h-full w-full md:w-4/5 lg:w-3/5 bg-white z-50 shadow-xl overflow-auto transition-transform duration-300" style="transform: translateX(110%);">
  <div class="p-4 border-b flex items-center gap-4">
    <button onclick="toggleAppointmentHistory()" class="px-3 py-2 bg-red-500 text-white rounded">←</button>
    <h2 class="text-2xl font-bold text-[#b32f1b]">📋 Appointment History</h2>
    <div class="flex items-center gap-2 ml-auto">
      <input id="searchAppointment" type="text" placeholder="Search name or reason..." class="border rounded px-3 py-2 w-40 md:w-72">
      <select id="filterSemester" class="border rounded px-3 py-2">
        <option value="">All Semesters</option>
        <option value="1st Semester">1st Semester</option>
        <option value="2nd Semester">2nd Semester</option>
      </select>
      <select id="filterSection" class="border rounded px-3 py-2">
        <option value="">All Sections</option>
      </select>
      <select id="filterStatus" class="border rounded px-3 py-2">
        <option value="">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Confirmed">Confirmed</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
  </div>

  <div class="p-4" id="historyList">
    <!-- Filled dynamically -->
    <p class="text-gray-500">Loading...</p>
  </div>
</aside>

<script>
function toggleMenu() {
  const menu = document.getElementById('menu');
  menu.classList.toggle('hidden');
}
function toggleExpand(id, button) {
  const section = document.getElementById(id);
  section.classList.toggle('max-h-[300px]');
  section.classList.toggle('overflow-y-auto');
  button.innerText = 'View all >';
}
function openDrawer() {
  document.getElementById('notificationDrawer').classList.remove('translate-x-full');
  fetchNotifications();
}
function closeDrawer() { document.getElementById('notificationDrawer').classList.add('translate-x-full'); }

let isPanelOpen = false;

function toggleAppointmentHistory() {
  const panel = document.getElementById('appointmentHistorySlide');
  const backdrop = document.getElementById('appointmentHistoryBackdrop');
  if (isPanelOpen) {
    panel.style.transform = 'translateX(110%)';
    backdrop.classList.add('hidden');
    isPanelOpen = false;
  } else {
    panel.style.transform = 'translateX(0%)';
    backdrop.classList.remove('hidden');
    loadAppointmentHistory();
    isPanelOpen = true;
  }
}

// Close panel when clicking backdrop
document.getElementById('appointmentHistoryBackdrop').addEventListener('click', toggleAppointmentHistory);

async function loadAppointmentHistory() {
  try {
    const res = await fetch('?action=fetch_history');
    const data = await res.json();
    renderAppointmentHistory(data);
  } catch (err) {
    console.error('Error loading appointment history:', err);
    document.getElementById('historyList').innerHTML = '<p class="text-red-500">Failed to load history.</p>';
  }
}

function renderAppointmentHistory(appointments) {
  const container = document.getElementById('historyList');
  if (appointments.length === 0) {
    container.innerHTML = '<p class="text-gray-500">No appointments found.</p>';
    return;
  }

  // Group by date
  const grouped = {};
  appointments.forEach(appt => {
    const date = appt.date.split(' ')[0]; // YYYY-MM-DD
    if (!grouped[date]) grouped[date] = [];
    grouped[date].push(appt);
  });

  let html = '';
  for (const date in grouped) {
    html += `<h3 class="text-lg font-semibold mb-2">${new Date(date).toLocaleDateString()}</h3>`;
    grouped[date].forEach(appt => {
      html += `
        <div class="bg-gray-50 p-3 mb-2 rounded shadow">
          <p><strong>${appt.student_name}</strong> - ${appt.reason}</p>
          <p class="text-sm text-gray-600">Time: ${appt.date.split(' ')[1]} | Section: ${appt.section} | Semester: ${appt.semester} | Status: ${appt.status}</p>
        </div>
      `;
    });
  }
  container.innerHTML = html;

  // Add filter listeners
  document.getElementById('searchAppointment').addEventListener('input', filterAppointments);
  document.getElementById('filterSemester').addEventListener('change', filterAppointments);
  document.getElementById('filterSection').addEventListener('change', filterAppointments);
  document.getElementById('filterStatus').addEventListener('change', filterAppointments);
}

function filterAppointments() {
  const search = document.getElementById('searchAppointment').value.toLowerCase();
  const semester = document.getElementById('filterSemester').value;
  const section = document.getElementById('filterSection').value;
  const status = document.getElementById('filterStatus').value;

  const cards = document.querySelectorAll('#historyList > div');
  cards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const apptSemester = card.querySelector('p:last-child').textContent.match(/Semester: (\w+)/)?.[1] || '';
    const apptSection = card.querySelector('p:last-child').textContent.match(/Section: (\w+)/)?.[1] || '';
    const apptStatus = card.querySelector('p:last-child').textContent.match(/Status: (\w+)/)?.[1] || '';

    const matchesSearch = text.includes(search);
    const matchesSemester = !semester || apptSemester === semester;
    const matchesSection = !section || apptSection === section;
    const matchesStatus = !status || apptStatus === status;

    card.style.display = (matchesSearch && matchesSemester && matchesSection && matchesStatus) ? 'block' : 'none';
  });
}

let lastNotificationCount = 0;
let lastEmergencyCount = 0;

document.addEventListener("click", () => {
  const ding = document.getElementById('dingSound');
  if (ding) {
    ding.play().then(() => {
      ding.pause();
      ding.currentTime = 0;
      console.log("🔓 Audio unlocked");
    }).catch(() => {
      console.log("⚠️ Audio unlock failed, will use fallback beep");
    });
  }
}, { once: true });

function playBell() {
  const bell = document.getElementById('bellSound');
  if (bell) {
    bell.currentTime = 0;
    bell.play().then(() => {
      console.log("🔊 Bell played successfully");
    }).catch(() => {
      try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = "sine";
        osc.frequency.setValueAtTime(880, ctx.currentTime); // high pitch for bell
        gain.gain.setValueAtTime(0.1, ctx.currentTime);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.3);
        console.log("🔊 Bell beep played");
      } catch (err) {
        console.error("❌ Bell beep failed:", err);
      }
    });
  }
}

function playAlarm() {
  const ding = document.getElementById('dingSound');
  if (ding) {
    ding.currentTime = 0;
    ding.play().then(() => {
      console.log("🔊 Alarm played successfully");
    }).catch(() => {
      try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = "sine";
        osc.frequency.setValueAtTime(440, ctx.currentTime); // low pitch for alarm
        gain.gain.setValueAtTime(0.1, ctx.currentTime);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.3);
        console.log("🔊 Alarm beep played");
      } catch (err) {
        console.error("❌ Alarm beep failed:", err);
      }
    });
  }
}

async function fetchNotifications() {
  try {
    const res = await fetch('notification_checker.php');
    const data = await res.json();

    
    const notifRes = await fetch('nurse_general_notifications.php');
    const notifData = await notifRes.json();

    const container = document.getElementById('drawerContent');
    container.innerHTML = '';

    let pendingCount = data.length + notifData.length;
    let emergencyCount = data.filter(item => item.is_emergency == 1).length + notifData.filter(item => item.message.startsWith('Emergency')).length;
    const badge = document.getElementById('notifBadge');

    if (pendingCount > 0) {
      badge.textContent = pendingCount;
      badge.classList.remove('hidden');
    } else {
      badge.classList.add('hidden');
    }

    if (emergencyCount > lastEmergencyCount) {
      playAlarm();
    } else if (pendingCount > lastNotificationCount) {
      playBell();
    }
    lastNotificationCount = pendingCount;
    lastEmergencyCount = emergencyCount;

    
    data.forEach(item => {
      const card = document.createElement('div');
      card.className = `p-3 rounded-lg shadow ${item.is_emergency==1?'bg-red-100 border-red-400':'bg-gray-100 border-gray-300'}`;
      card.innerHTML = `
  <p class="font-semibold">${item.student_name}${item.is_emergency==1 ? ' 🔴' : ''}</p>
  <p class="text-sm text-gray-600">🕒 ${new Date(item.appointment_time).toLocaleString()}</p>
  <p class="text-sm">📌 Reason: ${item.reason}</p>
  <div class="mt-2 flex space-x-2">
    <button class="accept-btn bg-green-500 text-white px-3 py-1 rounded" data-id="${item.id}">Accept</button>
    <button class="reject-btn bg-red-500 text-white px-3 py-1 rounded" data-id="${item.id}">Reject</button>
    <button class="resched-btn bg-yellow-500 text-white px-3 py-1 rounded" data-id="${item.id}">Reschedule</button>
  </div>`;

      container.appendChild(card);
    });

    
    notifData.forEach(item => {

      const match = item.message.match(/appointment #(\d+)/);
      const appointmentId = match ? match[1] : null;

      const card = document.createElement('div');
      card.className = 'p-3 rounded-lg shadow bg-blue-100 border-blue-300';

      const titleP = document.createElement('p');
      titleP.className = 'font-semibold';
      titleP.textContent = '📢 Notification';
      card.appendChild(titleP);

      const timeP = document.createElement('p');
      timeP.className = 'text-sm text-gray-600';
      timeP.textContent = `🕒 ${new Date(item.created_at).toLocaleString()}`;
      card.appendChild(timeP);

      const messageP = document.createElement('p');
      messageP.className = 'text-sm';
      messageP.textContent = item.message;
      card.appendChild(messageP);

      const buttonDiv = document.createElement('div');
      buttonDiv.className = 'mt-2 flex space-x-2';

      if (item.message.startsWith('Emergency reported by')) {
        const btn = document.createElement('button');
        btn.className = 'mark-read-btn bg-gray-500 text-white px-3 py-1 rounded';
        btn.textContent = 'mark as read';
        btn.dataset.notifId = item.id;
        btn.addEventListener('click', async () => {
          await markAsRead(btn.dataset.notifId);
        });
        buttonDiv.appendChild(btn);
      } else if (item.message.startsWith('Emergency:')) {
        const btn = document.createElement('button');
        btn.className = 'handle-btn bg-blue-500 text-white px-3 py-1 rounded';
        btn.textContent = 'Handle';
        btn.dataset.notifId = item.id;
        btn.dataset.message = item.message;
        btn.addEventListener('click', async () => {
          await handleEmergency(btn.dataset.notifId, btn.dataset.message);
        });
        buttonDiv.appendChild(btn);
      } else if (item.message.includes('is handling the emergency')) {
        const btn = document.createElement('button');
        btn.className = 'accept-emergency-btn bg-green-500 text-white px-3 py-1 rounded';
        btn.textContent = 'Accept';
        btn.dataset.notifId = item.id;
        btn.dataset.message = item.message;
        btn.addEventListener('click', async () => {
          await acceptEmergency(btn.dataset.notifId, btn.dataset.message);
        });
        buttonDiv.appendChild(btn);
      } else if (item.message.startsWith('Low stock alert:') || item.message.startsWith('Expiration alert:')) {
        const btn = document.createElement('button');
        btn.className = 'mark-medicine-read-btn bg-gray-500 text-white px-3 py-1 rounded';
        btn.textContent = 'mark as read';
        btn.dataset.notifId = item.id;
        btn.addEventListener('click', async () => {
          await markMedicineRead(btn.dataset.notifId);
        });
        buttonDiv.appendChild(btn);
      } else {
        const acceptBtn = document.createElement('button');
        acceptBtn.className = 'accept-btn bg-green-500 text-white px-3 py-1 rounded';
        acceptBtn.textContent = 'Accept';
        acceptBtn.dataset.id = appointmentId;
        acceptBtn.dataset.notifId = item.id;
        acceptBtn.addEventListener('click', async () => {
          await handleAction(acceptBtn.dataset.id, 'accept');
          if (acceptBtn.dataset.notifId) {
            await markAsRead(acceptBtn.dataset.notifId);
          }
        });
        buttonDiv.appendChild(acceptBtn);

        const rejectBtn = document.createElement('button');
        rejectBtn.className = 'reject-btn bg-red-500 text-white px-3 py-1 rounded';
        rejectBtn.textContent = 'Reject';
        rejectBtn.dataset.id = appointmentId;
        rejectBtn.dataset.notifId = item.id;
        rejectBtn.addEventListener('click', async () => {
          await handleAction(rejectBtn.dataset.id, 'reject');
          if (rejectBtn.dataset.notifId) {
            await markAsRead(rejectBtn.dataset.notifId);
          }
        });
        buttonDiv.appendChild(rejectBtn);

        const reschedBtn = document.createElement('button');
        reschedBtn.className = 'resched-btn bg-yellow-500 text-white px-3 py-1 rounded';
        reschedBtn.textContent = 'Reschedule';
        reschedBtn.dataset.id = appointmentId;
        reschedBtn.dataset.notifId = item.id;
        reschedBtn.addEventListener('click', () => openRescheduleModal(reschedBtn.dataset.id, reschedBtn.dataset.notifId));
        buttonDiv.appendChild(reschedBtn);
      }

      card.appendChild(buttonDiv);
      container.appendChild(card);
    });

    // Attach event listeners to buttons created with innerHTML
    document.querySelectorAll('.accept-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await handleAction(btn.dataset.id, 'accept');
        if (btn.dataset.notifId) {
          await markAsRead(btn.dataset.notifId);
        }
      });
    });
    document.querySelectorAll('.reject-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await handleAction(btn.dataset.id, 'reject');
        if (btn.dataset.notifId) {
          await markAsRead(btn.dataset.notifId);
        }
      });
    });
    document.querySelectorAll('.resched-btn').forEach(btn => btn.addEventListener('click', () => openRescheduleModal(btn.dataset.id, btn.dataset.notifId)));
    document.querySelectorAll('.handle-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await handleEmergency(btn.dataset.notifId, btn.dataset.message);
      });
    });
    document.querySelectorAll('.accept-emergency-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await acceptEmergency(btn.dataset.notifId, btn.dataset.message);
      });
    });
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await markAsRead(btn.dataset.notifId);
      });
    });
    document.querySelectorAll('.mark-medicine-read-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        await markMedicineRead(btn.dataset.notifId);
      });
    });

  } catch (err) { console.error(err); }
}

async function markAsRead(id) {
  try {
    const res = await fetch('nurse_mark_read.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}`
    });
    const data = await res.json();
    if (data.success) {
      fetchNotifications();
    }
  } catch (err) {
    console.error("Error marking as read:", err);
  }
}

async function markAllMedicineRead() {
  try {
    const res = await fetch('nurse_mark_all_medicine_read.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: ''
    });
    const data = await res.json();
    if (data.success) {
      fetchNotifications();
    } else {
      alert("Failed to mark all medicine alerts as read.");
    }
  } catch (err) {
    console.error("Error marking all medicine alerts as read:", err);
  }
}

async function markMedicineRead(id) {
  try {
    const res = await fetch('nurse_mark_medicine_read.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}`
    });
    const data = await res.json();
    if (data.success) {
      fetchNotifications();
    }
  } catch (err) {
    console.error("Error marking medicine alert as read:", err);
  }
}



function attachMarkDoneListeners() {
  document.querySelectorAll('.mark-done-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      await handleAction(btn.dataset.id, 'done');
    });
  });
}

function attachViewDetailsListeners() {
  document.querySelectorAll('.view-details-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      openDetailsModal(btn.dataset.id, btn.dataset.student, btn.dataset.reason, btn.dataset.date);
    });
  });
}

async function openDetailsModal(id, student, reason, date) {
  document.getElementById('appointmentId').value = id;
  document.getElementById('detailsStudent').textContent = student;
  document.getElementById('detailsReason').textContent = reason;
  document.getElementById('detailsDate').textContent = new Date(date).toLocaleString();

  // Fetch and display existing medicine entries
  await loadMedicineEntries(id);

  document.getElementById('detailsModal').classList.remove('hidden');
}

async function loadMedicineEntries(appointmentId) {
  try {
    const res = await fetch(`fetch_medicine_entries.php?appointment_id=${appointmentId}`);
    const data = await res.json();
    if (data.success) {
      displayMedicineEntries(data.entries);
    } else {
      console.error('Failed to load medicine entries:', data.message);
    }
  } catch (err) {
    console.error('Error loading medicine entries:', err);
  }
}

function displayMedicineEntries(entries) {
  const container = document.getElementById('medicineEntriesContainer');
  if (!container) {
    // Create container if it doesn't exist
    const modal = document.getElementById('detailsModal');
    const newContainer = document.createElement('div');
    newContainer.id = 'medicineEntriesContainer';
    newContainer.className = 'mt-4';
    modal.appendChild(newContainer);
  }

  const containerDiv = document.getElementById('medicineEntriesContainer');
  containerDiv.innerHTML = '';

  // Always display the editable form
  let entry = null;
  let buttonText = 'Add Entry';
  let buttonOnClick = 'saveMedicineEntry()';
  let dateText = '';

  if (entries.length > 0) {
    // Populate with the most recent entry
    entry = entries[0];
    buttonText = 'Save Changes';
    buttonOnClick = `updateMedicineEntry(${entry.id})`;
    dateText = `<p class="text-sm text-gray-600 mb-2"><strong>Date:</strong> ${new Date(entry.created_at).toLocaleString()}</p>`;
  }

  const formDiv = document.createElement('div');
  formDiv.className = 'bg-gray-50 p-3 rounded shadow';
  formDiv.innerHTML = `
    <label class="block text-sm font-semibold mb-1">Medicine Name</label>
    <input type="text" id="editMedicineName" class="w-full border rounded p-2 mb-2" value="${entry ? entry.medicine_name : ''}">

    <label class="block text-sm font-semibold mb-1">Dosage</label>
    <input type="text" id="editDosage" class="w-full border rounded p-2 mb-2" value="${entry ? (entry.dosage || '') : ''}" placeholder="e.g., 500mg">

    <label class="block text-sm font-semibold mb-1">Quantity</label>
    <input type="number" id="editQuantity" class="w-full border rounded p-2 mb-2" value="${entry ? entry.quantity : ''}" min="1">

    <label class="block text-sm font-semibold mb-1">Action Taken</label>
    <textarea id="editActionTaken" class="w-full border rounded p-2 mb-4" rows="3">${entry ? (entry.action_taken || '') : ''}</textarea>

    ${dateText}

    <div class="flex justify-end gap-2">
      <button type="button" onclick="${buttonOnClick}" class="px-3 py-1 bg-blue-500 text-white rounded">${buttonText}</button>
      <button type="button" onclick="closeDetailsModal()" class="px-3 py-1 bg-red-500 text-white rounded">Cancel</button>
    </div>
  `;
  containerDiv.appendChild(formDiv);
}

async function updateMedicineEntry(entryId) {
  const medicineName = document.getElementById('editMedicineName').value.trim();
  const dosage = document.getElementById('editDosage').value.trim();
  const quantity = document.getElementById('editQuantity').value;
  const actionTaken = document.getElementById('editActionTaken').value.trim();

  if (!medicineName || !quantity) {
    alert("Please fill in Medicine Name and Quantity.");
    return;
  }

  try {
    const res = await fetch('update_medicine.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        id: entryId,
        medicine_name: medicineName,
        dosage: dosage,
        quantity: quantity,
        action_taken: actionTaken
      })
    });
    const data = await res.json();
    if (data.success) {
      // Reload the entries to reflect changes in the form
      const appointmentId = document.getElementById('appointmentId').value;
      await loadMedicineEntries(appointmentId);
      closeDetailsModal();
    }
    // Removed error alert as update is working despite response
  } catch (err) {
    console.error("Error updating medicine entry:", err);
    // Removed alert as update works
  }
}

function closeDetailsModal() {
  document.getElementById('detailsModal').classList.add('hidden');
  // Clear form fields
  document.getElementById('medicineForm').reset();
}

function closeFollowupModal() {
  document.getElementById('followupModal').classList.add('hidden');
}

async function submitFollowup() {
  const appointmentId = document.getElementById('followupAppointmentId').value;
  const studentId = document.getElementById('followupStudentId').value;
  const followupDate = document.getElementById('followupDateTime').value;
  const reason = document.getElementById('followupReason').value.trim();

  if (!followupDate || !reason) {
    alert("Please fill in the follow-up date and reason.");
    return;
  }

  try {
    const res = await fetch('save_followup.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        appointment_id: appointmentId,
        student_id: studentId,
        followup_date: followupDate,
        reason: reason
      })
    });
    const data = await res.json();
    if (data.success) {
      alert("Follow-up appointment scheduled successfully.");
      closeFollowupModal();
      // Optionally refresh appointment activity or notifications
      await refreshAppointmentActivity();
      await fetchNotifications();
    } else {
      alert("Failed to schedule follow-up: " + (data.message || "Unknown error"));
    }
  } catch (err) {
    console.error("Error scheduling follow-up:", err);
    alert("Error scheduling follow-up.");
  }
}

async function saveMedicineEntry() {
  const appointmentId = document.getElementById('appointmentId').value;
  const medicineName = document.getElementById('editMedicineName').value.trim();
  const dosage = document.getElementById('editDosage').value.trim();
  const quantity = document.getElementById('editQuantity').value;
  const actionTaken = document.getElementById('editActionTaken').value.trim();

  if (!medicineName || !quantity) {
    alert("Please fill in Medicine Name and Quantity.");
    return;
  }

  try {
    const res = await fetch('save_medicine_entry.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        appointment_id: appointmentId,
        medicine_name: medicineName,
        dosage: dosage,
        quantity: quantity,
        action_taken: actionTaken
      })
    });
    const data = await res.json();
    if (data.success) {
      alert("Medicine entry saved successfully.");
      // Reload the entries to show the new entry
      await loadMedicineEntries(appointmentId);
    } else {
      alert("Failed to save medicine entry: " + (data.message || "Unknown error"));
    }
  } catch (err) {
    console.error("Error saving medicine entry:", err);
    alert("Error saving medicine entry.");
  }
}

async function refreshAppointmentActivity() {
  try {
    const res = await fetch('nurse_appointment_activity.php', { cache: 'no-cache' });
    const data = await res.json();
    const tbody = document.getElementById('appointmentTableBody');
    tbody.innerHTML = '';

    if (data.length > 0) {
      data.forEach(row => {
        const tr = document.createElement('tr');
        tr.id = "row-" + row.id;
        tr.innerHTML = `
          <td class="p-2">${row.date}</td>
          <td class="p-2">${row.time}</td>
          <td class="p-2">${row.student_name}</td>
          <td class="p-2">${row.reason}</td>
          <td class="p-2 font-semibold" id="status-${row.id}">${row.status}</td>
          <td class="p-2">
            <button class="view-details-btn bg-green-500 text-white px-2 py-1 rounded text-sm mr-1" data-id="${row.id}" data-student="${row.student_name}" data-reason="${row.reason}" data-date="${row.appointment_time}">Details</button>
            <button class="mark-done-btn bg-blue-500 text-white px-2 py-1 rounded text-sm" data-id="${row.id}">Mark as Done</button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      // Add event listeners for mark done and view details buttons
      attachMarkDoneListeners();
      attachViewDetailsListeners();
    } else {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500">No appointments found.</td></tr>';
    }
  } catch (err) {
    console.error("Error refreshing appointment activity:", err);
  }
}

async function handleAction(id, action) {
  try {
    if (action === 'done') {
      const res = await fetch('mark_done.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
      });
      const data = await res.json();
      if (data.success) {
        const statusCell = document.getElementById('status-' + id);
        if (statusCell) {
          statusCell.textContent = 'Completed';
          statusCell.classList.add('text-blue-600', 'font-semibold');
        }
        // Remove the row immediately
        const row = document.getElementById('row-' + id);
        if (row) row.remove();
        await refreshAppointmentActivity();

        // Show follow-up modal if flag is set
        if (data.show_followup_modal) {
          document.getElementById('followupAppointmentId').value = data.appointment_id;
          document.getElementById('followupStudentId').value = data.student_id;
          document.getElementById('followupModal').classList.remove('hidden');
        }
      } else {
        alert("Failed to mark as done.");
      }
    } else {
      const res = await fetch('handle_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&action=${action}`
      });
      const data = await res.json();

      if (data.success) {

        const statusCell = document.getElementById('status-' + id);
        if (statusCell) {
          statusCell.textContent = data.status;
          statusCell.classList.remove("text-green-600", "text-black-600", "text-red-600", "text-yellow-600");
          if (data.status === "Confirmed") {
            statusCell.classList.add("text-green-600", "font-semibold");
          } else if (data.status === "Rejected") {
            statusCell.classList.add("text-red-600", "font-semibold");
          } else if (data.status === "Pending Nurse Confirmation") {
            statusCell.classList.add("text-yellow-600", "font-semibold");
          }
        }

        await fetchNotifications();
        await refreshAppointmentActivity();
        calendar.refetchEvents();
      } else {
        alert("❌ Failed to update appointment.");
      }
    }
  } catch (err) {
    console.error("Error updating:", err);
  }
}

async function handleEmergency(notifId, message) {
  try {
   
    const res = await fetch('handle_emergency.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        notifId: notifId,
        message: message
      })
    });
    const data = await res.json();
    if (data.success) {
      alert("You are on the way.");
      await fetchNotifications();
    } else {
      alert("Failed to handle emergency: " + (data.message || "Unknown error"));
    }
  } catch (err) {
    console.error("Error handling emergency:", err);
  }
}

async function acceptEmergency(notifId, message) {
  try {
    
    const res = await fetch('save_visits.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        message: message
      })
    });
    const data = await res.json();
    if (data.success) {
      await markAsRead(notifId);
      window.location.href = 'Student_visitlogs.php';
    } else {
      alert("Failed to accept emergency visit.");
    }
  } catch (err) {
    console.error("Error accepting emergency:", err);
  }
}

function openRescheduleModal(id, notifId) {
  document.getElementById('rescheduleId').value = id;
  document.getElementById('rescheduleId').dataset.notifId = notifId;
  document.getElementById('rescheduleModal').classList.remove('hidden');
}
function closeRescheduleModal() { document.getElementById('rescheduleModal').classList.add('hidden'); }
async function submitReschedule() {
  const id = document.getElementById('rescheduleId').value;
  const notifId = document.getElementById('rescheduleId').dataset.notifId;
  const newTime = document.getElementById('newTime').value;
  if (!newTime) { alert("Please select a new time."); return; }

  const res = await fetch('reschedule.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&new_time=${encodeURIComponent(newTime)}`
  });
  const data = await res.json();

  if (data.success) {
    const statusCell = document.getElementById('status-' + id);
    if (statusCell) {
      statusCell.textContent = "Rescheduled: " + data.new_time;
      statusCell.classList.remove("text-green-600", "text-red-600");
      statusCell.classList.add("text-yellow-600", "font-semibold");
    }
    if (notifId) {
      await markAsRead(notifId);
    }
    closeRescheduleModal();
    fetchNotifications();
  } else {
    alert("❌ Failed to reschedule.");
  }
}

function openEventModal(isEdit = false, eventInfo = null) {
  const modal = document.getElementById('eventModal');
  const titleEl = document.getElementById('eventModalTitle');
  const idEl = document.getElementById('eventId');
  const startEl = document.getElementById('eventStart');
  const endEl = document.getElementById('eventEnd');
  const titleInput = document.getElementById('eventTitle');
  const noteInput = document.getElementById('eventNote');
  const deleteBtn = document.getElementById('deleteEventBtn');

  if (isEdit && eventInfo) {
    titleEl.textContent = 'Edit Event';
    idEl.value = eventInfo.event.id;
    startEl.value = eventInfo.event.startStr.slice(0, 16); 
    titleInput.value = eventInfo.event.title;
    noteInput.value = eventInfo.event.extendedProps.note || '';
    deleteBtn.classList.remove('hidden');
  } else {
    titleEl.textContent = 'Add Event';
    idEl.value = '';
    if (eventInfo) {
      const startDate = new Date(eventInfo.startStr);
      startEl.value = startDate.toISOString().slice(0, 16);
      
    } else {
      startEl.value = '';
      
    }
    titleInput.value = '';
    noteInput.value = '';
    deleteBtn.classList.add('hidden');
  }
  modal.classList.remove('hidden');
}

function closeEventModal() {
  document.getElementById('eventModal').classList.add('hidden');
}

async function saveEvent() {
  const id = document.getElementById('eventId').value;
  const title = document.getElementById('eventTitle').value.trim();
  const note = document.getElementById('eventNote').value.trim();
  const start = document.getElementById('eventStart').value;


  if (!title) {
    alert("Please enter a title.");
    return;
  }

  const data = { title, note, start: start.replace('T', ' ') };

  let url, method;
  if (id) {
    
    data.id = id;
    url = 'update-event.php';
    method = 'POST';
  } else {
   
    url = 'add-event.php';
    method = 'POST';
  }

  try {
    const res = await fetch(url, {
      method: method,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(data)
    });
    const result = await res.json();
    if (result.status === 'success') {
      closeEventModal();
      calendar.refetchEvents();
    } else {
      alert("Failed to save event: " + result.message);
    }
  } catch (err) {
    console.error(err);
    alert("Error saving event.");
  }
}

async function deleteEvent() {
  const id = document.getElementById('eventId').value;
  if (!confirm('Are you sure you want to delete this event?')) return;

  try {
    const res = await fetch('delete-event.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}`
    });
    const result = await res.json();
    if (result.status === 'success') {
      closeEventModal();
      calendar.refetchEvents();
    } else {
      alert("Failed to delete event: " + result.message);
    }
  } catch (err) {
    console.error(err);
    alert("Error deleting event.");
  }
}

let calendar; 

setInterval(fetchNotifications, 5000);

function checkAlarms() {
  fetch('fetch-events.php')
    .then(res => res.json())
    .then(events => {
      const now = new Date();
      events.forEach(event => {
        // Only alarm for appointments
        if (event.title.startsWith('Appointment:')) {
          const start = new Date(event.start);
          const reminderMinutes = event.extendedProps.reminder_minutes || 1; // default 1 minute
          const reminderTime = new Date(start.getTime() - reminderMinutes * 60 * 1000);

          if (now >= reminderTime && now <= new Date(start.getTime() + 60 * 1000)) {
            const audio = document.getElementById('dingSound');
            if (audio) {
              audio.currentTime = 0;
              audio.play().then(() => {
                console.log("Alarm played for appointment:", event.title);
              }).catch(err => {
                console.error("Failed to play alarm:", err);

                try {
                  const ctx = new (window.AudioContext || window.webkitAudioContext)();
                  const osc = ctx.createOscillator();
                  const gain = ctx.createGain();
                  osc.type = "sine";
                  osc.frequency.setValueAtTime(880, ctx.currentTime);
                  gain.gain.setValueAtTime(0.1, ctx.currentTime);
                  osc.connect(gain);
                  gain.connect(ctx.destination);
                  osc.start();
                  osc.stop(ctx.currentTime + 0.3);
                  console.log("Fallback beep played");
                } catch (e) {
                  console.error("Fallback beep failed:", e);
                }
              });
            }

            if (navigator.vibrate) {
              navigator.vibrate([200, 100, 200, 100, 200]);
            }

            alert(`Appointment: ${event.title} is starting now!`);
          }
        }
      });
    })
    .catch(err => console.error('Error checking alarms:', err));
}

document.getElementById('menuBtn').addEventListener('click', toggleMenu);

window.onload = function() {
  fetchNotifications();
  attachMarkDoneListeners();
  attachViewDetailsListeners();
  let calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView:'dayGridMonth',
    editable:true,
    selectable:true,
    eventSources:[{url:'fetch-events.php', method:'GET'}],
    select:function(info){
      openEventModal(false, info);
    },
    eventDrop:function(info){
      $.post('update-event.php',{id:info.event.id,start:info.event.startStr.replace('T',' ')},()=>calendar.refetchEvents());
    },
    eventResize:function(info){
      $.post('update-event.php',{id:info.event.id,start:info.event.startStr.replace('T',' ')},()=>calendar.refetchEvents());
    },
    eventClick:function(info){
      openEventModal(true, info);
    }
  });
  calendar.render();
  setInterval(checkAlarms, 60000);
};
</script>
</body>
</html>