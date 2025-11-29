<?php
// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "capstone1";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Database Connection Failed: " . $conn->connect_error);
}

// --- CURRENT YEAR ---
$currentYear = date("Y");

// --- CHECK IF CURRENT YEAR DATA EXISTS ---
$sql = "SELECT * FROM clinic_utilization WHERE year = '$currentYear'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  // if no data for current year, insert a new blank record
  $conn->query("INSERT INTO clinic_utilization (year, total_visits, return_visits, emergency_cases, health_concerns, date_generated)
                VALUES ('$currentYear', 0, 0, 0, 0, NOW())");
}

// --- FETCH CURRENT YEAR DATA ---
$dataQuery = $conn->query("SELECT * FROM clinic_utilization WHERE year = '$currentYear'");
$currentData = $dataQuery->fetch_assoc();

// --- CALCULATE AND UPDATE CURRENT YEAR DATA ---
// Calculate student_registered: count of distinct students who visited in the year
$studentRegisteredQuery = $conn->query("SELECT COUNT(DISTINCT student_id) as registered FROM student_visits WHERE YEAR(visit_date) = '$currentYear'");
$studentRegistered = $studentRegisteredQuery->fetch_assoc()['registered'];

// Calculate total_visits: count of visits from student_visits table for the year
$totalVisitsQuery = $conn->query("SELECT COUNT(*) as total FROM student_visits WHERE YEAR(visit_date) = '$currentYear'");
$totalVisits = $totalVisitsQuery->fetch_assoc()['total'];

// Calculate emergency_cases: count of notifications with message starting with 'Emergency' or appointments with is_emergency=1
$emergencyNotificationsQuery = $conn->query("SELECT COUNT(*) as emerg FROM notifications WHERE YEAR(created_at) = '$currentYear' AND message LIKE 'Emergency%'");
$emergencyNotifications = $emergencyNotificationsQuery->fetch_assoc()['emerg'];

$emergencyAppointmentsQuery = $conn->query("SELECT COUNT(*) as emerg FROM appointments WHERE YEAR(appointment_time) = '$currentYear' AND is_emergency = 1");
$emergencyAppointments = $emergencyAppointmentsQuery->fetch_assoc()['emerg'];

$emergencyCases = $emergencyNotifications + $emergencyAppointments;

// Calculate health_concerns: count of appointments with status 'Confirmed' or 'Pending' (assuming these indicate health concerns)
$healthConcernsQuery = $conn->query("SELECT COUNT(*) as concerns FROM appointments WHERE YEAR(appointment_time) = '$currentYear' AND status IN ('Pending', 'Confirmed')");
$healthConcerns = $healthConcernsQuery->fetch_assoc()['concerns'];

// Update the clinic_utilization table for the current year
$updateSql = "UPDATE clinic_utilization SET total_visits = ?, return_visits = ?, emergency_cases = ?, health_concerns = ?, date_generated = NOW() WHERE year = ?";
$stmt = $conn->prepare($updateSql);
$stmt->bind_param("iiiis", $studentRegistered, $totalVisits, $emergencyCases, $healthConcerns, $currentYear);
$stmt->execute();
$stmt->close();

// Re-fetch updated data
$dataQuery = $conn->query("SELECT * FROM clinic_utilization WHERE year = '$currentYear'");
$currentData = $dataQuery->fetch_assoc();

// --- FETCH ALL YEARS FOR ARCHIVE ---
$archiveQuery = $conn->query("SELECT * FROM clinic_utilization ORDER BY year ASC");
$archiveData = [];
while ($row = $archiveQuery->fetch_assoc()) {
  $archiveData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clinic Utilization Summary – <?php echo $currentYear; ?></title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #b71c1c;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 40px;
    }
    .report-container {
      background: white;
      color: black;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      width: 560px;
    }
    .report-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
    }
    .report-header h1 {
      font-size: 1.8rem;
      margin: 0;
    }
    canvas {
      margin-top: 20px;
    }
    .archive {
      margin-top: 30px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #f5f5f5;
    }
  </style>
</head>
<body>
  <div class="report-container">
    <div class="report-header">
      <button onclick="history.back()" style="padding: 10px 20px; background-color: #b71c1c; color: white; border: none; border-radius: 5px; cursor: pointer;">Back</button>
      <div>
        <h1>Clinic Utilization Summary  <br>Year <?php echo $currentYear; ?></h1>
        <p>Generated by: School Health Clinic Management System</p>
      </div>
    </div>


    <canvas id="clinicChart" width="600" height="400"></canvas>

    <div class="archive">
      <h3>📦 Archive Reports</h3>
      <table>
        <tr>
          <th>Year</th>
          <th>Student Registered</th>
          <th>Total Visits</th>
          <th>Emergency Cases</th>
          <th>Health Concerns</th>
        </tr>
        <?php foreach ($archiveData as $row): ?>
        <tr>
          <td><?php echo $row['year']; ?></td>
          <td><?php echo $row['total_visits']; ?></td>
          <td><?php echo $row['return_visits']; ?></td>
          <td><?php echo $row['emergency_cases']; ?></td>
          <td><?php echo $row['health_concerns']; ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
c
  <script>
    const ctx = document.getElementById('clinicChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Student Registered', 'Total Visits', 'Emergency Cases', 'Health Concerns'],
        datasets: [{
          label: 'Total (<?php echo $currentYear; ?>)',
          data: [
            <?php echo $currentData['total_visits']; ?>,
            <?php echo $currentData['return_visits']; ?>,
            <?php echo $currentData['emergency_cases']; ?>,
            <?php echo $currentData['health_concerns']; ?>
          ],
          backgroundColor: ['#2196F3', '#4CAF50', '#FF9800', '#9E9E9E'],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Count' }
          },
          x: {
            title: { display: true, text: 'Metric' }
          }
        }
      }
    });
  </script>
</body>
</html>