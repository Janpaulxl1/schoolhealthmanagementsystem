<?php
session_start();
require_once 'db.php';

$result_medications = false;
if ($conn) {
    $sql = "SELECT * FROM medications ORDER BY id ASC";
    $result_medications = $conn->query($sql);
}

// Function to send notification to nurse
function sendNotificationToNurse($message) {
    global $conn;
    // Get nurse user_id
    $sql = "SELECT u.id FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'nurse' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)");
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Mark medicine and appointment-related notifications as read when dashboard is viewed
if ($conn) {
    $sql = "SELECT u.id FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'nurse' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nurse_id = $row['id'];
        $update_sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND (message LIKE '%Low stock alert%' OR message LIKE '%Expiration alert%' OR message LIKE '%reject%' OR message LIKE '%accept%' OR message LIKE '%reschedule%') AND is_read = 0";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $nurse_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medication Supplies Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#BB1D1D]"></body>

<header class="bg-gradient-to-r from-orange-400 to-red-700 text-white p-4 flex justify-between items-center relative">
  <div class="flex items-center gap-4">
    <button onclick="document.getElementById('menu').classList.toggle('hidden')">☰</button>
    <h1 class="font-bold text-xl">Admin Dashboard</h1>
  </div>
  <div class="absolute right-1 -bottom-12 z-10">
    <img src="images/Logo.png" alt="Logo" class="w-32 h-31 rounded-full">
  </div>
</header>

<!-- SIDEBAR MENU -->
<div id="menu" class="absolute bg-white w-64 p-4 hidden top-[65px] left-0 shadow z-10 rounded-md">
  <h2 class="text-lg mb-4 font-semibold">File Clinic Explorer</h2>
  <hr class="mb-2">
  <a class="block mb-4" href="nurse.php">↩️ Go Back</a>
  <details class="group mb-4">
    <summary class="cursor-pointer flex items-center hover:bg-gray-300 transition p-2 rounded">📁 Documents</summary>
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
  <a class="block mb-4" href="emergency_reports.php">📁 Emergency Reports</a>
  <a class="block mb-4" href="responder_status.php">📁 Responder Status</a>
</div>

<!-- NURSE INFO -->
<section class="bg-white p-4 mt-4 max-w-6xl mx-auto flex items-center rounded shadow">
  <img src="images/Nurse.jpg" class="w-20 h-20 rounded-full border-4 border-green-500" alt="Nurse">
  <div class="ml-4">
    <h2 class="text-xl font-semibold">Mrs. Lorefe F. Verallo</h2>
    <p class="text-gray-500 text-sm">Nurse ID: 2022001</p>
  </div>
</section>

<!-- MEDICATION TABLE -->
<section class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded shadow">
  <div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-bold">MEDICAL SUPPLIES</h3>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
            class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
      + Add Medicine
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full border text-sm">
      <thead class="bg-blue-300">
        <tr>
          <th class="border p-2 text-left">Med_ID</th>
          <th class="border p-2 text-left">Item Name</th>
          <th class="border p-2 text-left">Dosage</th>
          <th class="border p-2 text-left">Usage</th>
          <th class="border p-2 text-left">Quantity</th>
          <th class="border p-2 text-left">Expiration Date</th>
          <th class="border p-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result_medications && $result_medications->num_rows > 0): ?>
          <?php while($row = $result_medications->fetch_assoc()): ?>
            <?php
            $quantity_class = $row['quantity'] <= 10 ? 'bg-red-100 text-red-800' : '';
            $expiration_class = strtotime($row['expiration_date']) < time() ? 'bg-red-100 text-red-800' : '';

            // Send notifications for low stock or expired items
            if ($row['quantity'] <= 10) {
                sendNotificationToNurse("Low stock alert: " . htmlspecialchars($row['name']) . " has only " . $row['quantity'] . " items left.");
            }
            if (strtotime($row['expiration_date']) < time()) {
                sendNotificationToNurse("Expiration alert: " . htmlspecialchars($row['name']) . " has expired on " . htmlspecialchars($row['expiration_date']) . ".");
            }
            ?>
            <tr>
              <td class="border p-2"><?= htmlspecialchars($row['id']) ?></td>
              <td class="border p-2"><?= htmlspecialchars($row['name']) ?></td>
              <td class="border p-2"><?= htmlspecialchars($row['dosage']) ?></td>
              <td class="border p-2"><?= htmlspecialchars($row['instructions']) ?></td>
              <td class="border p-2 <?= $quantity_class ?>"><?= htmlspecialchars($row['quantity']) ?><?php if ($row['quantity'] <= 10) echo ' ⚠️ Low Stock'; ?></td>
              <td class="border p-2 <?= $expiration_class ?>"><?= htmlspecialchars($row['expiration_date']) ?><?php if (strtotime($row['expiration_date']) < time()) echo ' ⚠️ Expired'; ?></td>
              <td class="border p-2 text-center">
                <button
                  data-id="<?= $row['id'] ?>"
                  data-name="<?= htmlspecialchars($row['name']) ?>"
                  data-dosage="<?= htmlspecialchars($row['dosage']) ?>"
                  data-instructions="<?= htmlspecialchars($row['instructions']) ?>"
                  data-quantity="<?= htmlspecialchars($row['quantity']) ?>"
                  data-expiration-date="<?= htmlspecialchars($row['expiration_date']) ?>"
                  onclick="openEditModal(this)"
                  class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 mr-1">
                  ✏️
                </button>
                <form action="delete_medicine.php" method="POST" class="inline">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">🗑️</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-gray-500 py-4">No medication data found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ADD MODAL -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
    <h2 class="text-xl font-bold mb-4">Add Medicine</h2>
    <form action="add_medicine.php" method="POST" class="space-y-3">
      <input type="text" name="name" placeholder="Medicine Name" class="w-full border p-2 rounded" required>
      <input type="text" name="dosage" placeholder="Dosage" class="w-full border p-2 rounded">
      <textarea name="instructions" placeholder="Instructions / Usage" class="w-full border p-2 rounded"></textarea>
      <input type="number" name="quantity" placeholder="Quantity" class="w-full border p-2 rounded" required>
      <input type="date" name="expiration_date" class="w-full border p-2 rounded" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-3 py-2 border rounded">Cancel</button>
        <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">Save</button>
      </div>
    </form>
  </div>
</div> <!-- ✅ THIS CLOSING DIV WAS MISSING -->

<!-- EDIT MODAL -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
    <h2 class="text-xl font-bold mb-4">Edit Medicine</h2>
    <form action="update_medicine.php" method="POST" class="space-y-3">
      <input type="hidden" name="id" id="edit_id">
      <input type="text" name="name" id="edit_name" placeholder="Medicine Name" class="w-full border p-2 rounded" required>
      <input type="text" name="dosage" id="edit_dosage" placeholder="Dosage" class="w-full border p-2 rounded">
      <textarea name="instructions" id="edit_instructions" placeholder="Instructions / Usage" class="w-full border p-2 rounded"></textarea>
      <input type="number" name="quantity" id="edit_quantity" placeholder="Quantity" class="w-full border p-2 rounded" required>
      <input type="date" name="expiration_date" id="edit_expiration_date" class="w-full border p-2 rounded" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-3 py-2 border rounded">Cancel</button>
        <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(button) {
  const id = button.getAttribute('data-id');
  const name = button.getAttribute('data-name');
  const dosage = button.getAttribute('data-dosage');
  const instructions = button.getAttribute('data-instructions');
  const quantity = button.getAttribute('data-quantity');
  const expiration_date = button.getAttribute('data-expiration-date');

  document.getElementById('edit_id').value = id;
  document.getElementById('edit_name').value = name;
  document.getElementById('edit_dosage').value = dosage;
  document.getElementById('edit_instructions').value = instructions;
  document.getElementById('edit_quantity').value = quantity;
  document.getElementById('edit_expiration_date').value = expiration_date;

  document.getElementById('editModal').classList.remove('hidden');
}
</script>

</body>
</html>

<?php if ($conn) $conn->close(); ?>