<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
require_once 'db.php';

$result_resp = false;

if ($conn) {
    $sql_resp = "SELECT name, status FROM emergency_responders ORDER BY name ASC";
    $result_resp = $conn->query($sql_resp);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Emergency Responder Status</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background-color: #CBC8C8; }
  </style>
</head>
<body class="bg-[#BB1D1D]"></body>

  <!-- HEADER -->
  <header class="bg-gradient-to-r from-orange-400 to-red-600 text-white py-3 px-6 flex justify-between items-center shadow relative">
    <div class="flex items-center space-x-3">
      <button id="menuBtn" class="text-2xl cursor-pointer">&#9776;</button>
      <h1 class="text-xl font-bold">AdminDashboard</h1>
    </div>
    <div>
     <img src="images/logo.png" alt="Logo" class="absolute right-4 top-2 w-32 h-32 rounded-full object-cover z-10">
    </div>
  </header>

  <!-- Dropdown Menu -->
  <div id="menu" class="absolute bg-white w-64 p-4 hidden top-[65px] left-0 shadow z-10 rounded-md">
    <div class="flex items-center mb-4">
      <h2 class="text-lg font-semibold">File Clinic Explorer</h2>
    </div>
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

  <!-- MAIN CONTAINER -->
  <div class="max-w-6xl mx-auto bg-white mt-8 rounded-xl shadow-lg overflow-hidden">

    <!-- Nurse Profile -->
    <div class="flex items-center border-b border-gray-300 px-6 py-4">
      <div class="flex items-center space-x-4">
        <img src="images/Nurse.jpg" alt="Profile" class="w-20 h-20 rounded-full border-4 border-green-500 object-cover">
        <div>
          <h2 class="text-xl font-bold">Mrs. Lorefe F. Verallo</h2>
          <p class="text-gray-600">Nurse ID: 2022001</p>
        </div>
      </div>
    </div>

    <!-- Emergency Responder Status -->
    <div class="px-6 py-4">
      <h3 class="text-lg font-bold text-red-600 mb-3 flex items-center">
        🚨 Emergency Responder Status
      </h3>

      <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full border-collapse">
          <thead class="bg-red-500 text-white">
            <tr>
              <th class="p-2 text-left">Name</th>
              <th class="p-2 text-left">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white text-black">
            <?php if ($result_resp && $result_resp->num_rows > 0): ?>
              <?php while($row = $result_resp->fetch_assoc()): ?>
                <tr class="border-b hover:bg-red-50">
                  <td class="p-2"><?php echo htmlspecialchars($row['name']); ?></td>
                  <td class="p-2 text-green-600 font-semibold"><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="2" class="text-center text-gray-500 p-4">No responders found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('menu');
      menu.classList.toggle('hidden');
    }

    document.getElementById('menuBtn').addEventListener('click', toggleMenu);
  </script>
</body>
</html>