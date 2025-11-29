<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['student_id'])) {
    die("❌ Please log in as a student first.");
}

$student_id = $_SESSION['student_id']; 

// Fetch student info
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("❌ Student not found (ID: $student_id).");
}

$fullname = $student['first_name'] . " " . $student['last_name'];

// Fetch student appointments with associated medicine entries
$stmt = $conn->prepare("SELECT a.id, a.appointment_time, a.reason, a.status,
                               am.medicine_name, am.dosage, am.quantity, am.action_taken, am.created_at
                        FROM appointments a
                        LEFT JOIN appointment_medications am ON a.id = am.appointment_id
                        WHERE a.student_id = ?
                        ORDER BY a.appointment_time DESC, am.created_at DESC");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$medical_history = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<div class="flex justify-between items-center bg-red-600 p-4 text-white relative">
  <button id="menuBtn" class="text-2xl z-50">&#9776;</button>
  <h1 class="text-lg font-bold">Student Profile</h1>
  <div class="relative">
    <button id="notifBtn" class="text-2xl relative">&#128276;
      <span id="notifBadge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-1 rounded-full"></span>
    </button>
    <!-- Notification Box -->
    <div id="notifBox" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white shadow-lg rounded-lg p-4 text-black z-50 max-h-96 overflow-y-auto">
      <h3 class="font-semibold mb-2">Notifications</h3>
      <div id="notifList">
        <p class="text-sm text-gray-500">Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 w-64 max-w-full h-full bg-red-700 text-white p-4 z-40 transform -translate-x-full transition-transform duration-300 md:w-72">
  <div class="mt-16 space-y-2">
    <a href="appointments.php" class="block py-2 px-2 rounded hover:bg-red-500">📅 Book Appointment</a>
    <a href="report_emergency.php" class="block py-2 px-2 rounded hover:bg-red-500">🚨 Report Emergency</a>
    <a href="logout.php" class="block py-2 px-2 rounded hover:bg-red-500">🚪 Logout</a>
  </div>
</div>

<!-- Main Content -->
<div class="p-4 sm:p-6 max-w-6xl mx-auto">
  <!-- Emergency Responder Banner -->
  <?php if ($student['is_responder'] == 1): ?>
  <div class="bg-red-500 text-white text-center py-2 font-bold mb-4 rounded">
    🚨 Emergency Responder
  </div>
  <?php endif; ?>

  <!-- Profile Card -->
  <div class="bg-white shadow rounded-lg p-4 sm:p-6 mb-6 flex flex-col sm:flex-row items-center sm:items-start">
    <img src="<?= htmlspecialchars($student['profile_picture'] ?: 'default.png') ?>" 
         alt="Profile"
         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border mb-4 sm:mb-0 sm:mr-4" />
    <div class="text-center sm:text-left">
      <h2 class="text-xl sm:text-2xl font-bold"><?= htmlspecialchars($fullname) ?></h2>
      <p class="text-gray-600 text-sm sm:text-base">
        <?= htmlspecialchars($student['section']) ?>
      </p>
      <p class="text-gray-600 text-sm sm:text-base">Student ID: <?= htmlspecialchars($student['student_id']) ?></p>
    </div>
  </div>

  <!-- Tabs -->
  <div class="flex flex-wrap gap-2 mb-6">
    <button class="tab-btn px-3 py-2 bg-red-600 text-white rounded text-sm sm:text-base" data-tab="medical_history">Medical History</button>
    <button class="tab-btn px-3 py-2 bg-gray-200 text-black rounded text-sm sm:text-base" data-tab="about">About</button>
  </div>

  <!-- Medical History -->
  <div id="medical_history" class="tab-content">
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 overflow-x-auto">
      <?php if ($medical_history && $medical_history->num_rows > 0): ?>
        <table class="w-full min-w-[800px] border border-collapse text-sm sm:text-base">
          <thead>
            <tr class="bg-gray-200 text-left">
              <th class="p-2 border border-gray-300">Appointment Date</th>
              <th class="p-2 border border-gray-300">Reason</th>
              <th class="p-2 border border-gray-300">Status</th>
              <th class="p-2 border border-gray-300">Medicine Name</th>
              <th class="p-2 border border-gray-300">Dosage</th>
              <th class="p-2 border border-gray-300">Quantity</th>
              <th class="p-2 border border-gray-300">Action Taken</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $medical_history->fetch_assoc()): ?>
              <tr>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars(date('m/d/Y h:i A', strtotime($row['appointment_time']))) ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['reason']) ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['status']) ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['medicine_name'] ?? 'N/A') ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['dosage'] ?? 'N/A') ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['quantity'] ?? 'N/A') ?></td>
                <td class="p-2 border border-gray-300"><?= htmlspecialchars($row['action_taken'] ?? 'N/A') ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-gray-500">No medical history found.</p>
      <?php endif; ?>
    </div>
  </div>

  <div id="about" class="tab-content hidden">
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
      <h3 class="text-lg font-semibold mb-4">About</h3>
      <p><strong>Birthday:</strong> <?= htmlspecialchars($student['birthday'] ?? 'N/A') ?></p>
      <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender'] ?? 'N/A') ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($student['email'] ?? 'N/A') ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? 'N/A') ?></p>
      <p><strong>Home Address:</strong> <?= htmlspecialchars($student['home_address'] ?? 'N/A') ?></p>
      <p><strong>Guardian:</strong> <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?></p>
      <p><strong>Guardian Phone:</strong> <?= htmlspecialchars($student['emergency_contact'] ?? 'N/A') ?></p>
    </div>
  </div>
</div>


<audio id="dingSound" preload="auto">
  <source src="bell.mp3" type="audio/mpeg" />
</audio>

<script>
  const sidebar = document.getElementById("sidebar");
  const menuBtn = document.getElementById("menuBtn");
  const notifBtn = document.getElementById("notifBtn");
  const notifBox = document.getElementById("notifBox");
  let lastNotificationCount = 0;
  const ding = document.getElementById("dingSound");

  
  menuBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    sidebar.classList.toggle("-translate-x-full");
    sidebar.classList.toggle("translate-x-0");
  });

  
  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
      sidebar.classList.add("-translate-x-full");
      sidebar.classList.remove("translate-x-0");
    }
  });

  
  document.querySelectorAll(".tab-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("bg-red-600", "text-white"));
      document.querySelectorAll(".tab-btn").forEach(b => b.classList.add("bg-gray-200", "text-black"));
      btn.classList.add("bg-red-600", "text-white");
      btn.classList.remove("bg-gray-200", "text-black");

      document.querySelectorAll(".tab-content").forEach(c => c.classList.add("hidden"));
      document.getElementById(btn.dataset.tab).classList.remove("hidden");
    });
  });

  
  document.body.addEventListener("click", () => {
    if (ding) {
      ding.play().then(() => {
        ding.pause();
        ding.currentTime = 0;
      }).catch(err => console.warn("Could not play notification sound", err));
    }
  });

  async function fetchNotifications() {
    try {
      const res = await fetch("student_notification_checker.php");
      const data = await res.json();

      const list = document.getElementById("notifList");
      list.innerHTML = "";
      let unread = 0;

      if (!data.notifications || data.notifications.length === 0) {
        list.innerHTML = "<p class='text-gray-500 text-sm'>No notifications found.</p>";
      } else {
        data.notifications.forEach(note => {
          const div = document.createElement("div");
          div.className = "border-b pb-1 mb-1 " + (note.is_read == 1 ? "text-gray-500" : "font-semibold text-black");
          let innerHTML = `${note.message}<br><small class="text-gray-500">${new Date(note.created_at).toLocaleString()}</small>`;

          if (note.reschedule_status === 'pending') {
            innerHTML += `<br>
              <button class="accept-resched bg-green-500 text-white px-2 py-1 rounded text-xs mr-1" 
                data-appointment-id="${note.appointment_id}" data-notification-id="${note.id}">Accept</button>
              <button class="decline-resched bg-red-500 text-white px-2 py-1 rounded text-xs" 
                data-appointment-id="${note.appointment_id}" data-notification-id="${note.id}">Decline</button>`;
          } else if (note.reschedule_status === 'accepted') {
            innerHTML += `<br><span class="text-green-600 text-xs">You accepted this reschedule.</span>`;
          } else if (note.reschedule_status === 'declined' || note.reschedule_status === 'rejected') {
            innerHTML += `<br><span class="text-red-600 text-xs">You declined this reschedule.</span>`;
          }
          div.innerHTML = innerHTML;
          list.appendChild(div);
          if (note.is_read == 0) unread++;
        });
      }

      const badge = document.getElementById("notifBadge");
      if (unread > 0) {
        badge.textContent = unread;
        badge.classList.remove("hidden");
      } else {
        badge.classList.add("hidden");
      }

      if (unread > lastNotificationCount) {
        playBell();
      }
      lastNotificationCount = unread;
    } catch (e) {
      console.error("fetchNotifications error:", e);
    }
  }

  function playBell() {
    if (ding) {
      ding.play().catch(err => console.warn("Error playing notification sound", err));
    }
  }

  notifBtn.addEventListener("click", async (e) => {
    e.stopPropagation();
    notifBox.classList.toggle("hidden");
    if (!notifBox.classList.contains("hidden")) {
      await fetchNotifications();
      try {
        await fetch("student_mark_read.php", { method: "POST" });
        fetchNotifications();
      } catch (err) {
        console.error("Failed to mark notifications as read", err);
      }
    }
  });

  document.addEventListener("click", () => {
    notifBox.classList.add("hidden");
  });

  notifBox.addEventListener('click', e => {
    e.stopPropagation();
    const target = e.target;
    if (target.classList.contains('accept-resched') || target.classList.contains('decline-resched')) {
      const appointmentId = target.dataset.appointmentId;
      const notificationId = target.dataset.notificationId;
      const response = target.classList.contains('accept-resched') ? 'accept' : 'reject';
      if (!appointmentId || !notificationId) {
        console.error('Missing appointmentId or notificationId');
        return;
      }
      handleRescheduleResponse(notificationId, appointmentId, response, target);
    }
  });

  async function handleRescheduleResponse(notificationId, appointmentId, response, clickedBtn = null) {
    try {
      if (clickedBtn) {
        const parent = clickedBtn.closest('div');
        parent.querySelectorAll('button').forEach(b => b.disabled = true);
      }

      const res = await fetch('student_reschedule_response.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          notificationId: notificationId,
          appointmentId: appointmentId,
          action: response === 'accept' ? 'accepted' : 'declined'
        })
      });

      if (!res.ok) throw new Error(`Network error: ${res.status}`);

      const data = await res.json();
      if (data.status === 'success') {
        if (clickedBtn) {
          const notifItem = clickedBtn.closest('div');
          if (response === 'accept') {
            notifItem.innerHTML = `<p class="text-green-600">✅ You accepted the reschedule.</p>`;
          } else {
            notifItem.innerHTML = `<p class="text-red-600">❌ You declined the reschedule.</p>`;
          }
        } else {
          fetchNotifications();
        }
      } else {
        alert(data.message || 'Failed to respond to reschedule.');
        if (clickedBtn) clickedBtn.disabled = false;
      }
    } catch (err) {
      alert(err.message);
      if (clickedBtn) clickedBtn.disabled = false;
    }
  }

  
  setInterval(fetchNotifications, 5000);
  fetchNotifications();
</script>

</body>
</html>
