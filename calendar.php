<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8' />
  <title>Local FullCalendar</title>

  <!-- ✅ Local FullCalendar files -->
  <link href="index.main.min.css" rel="stylesheet" />
  <script src="index.main.min.js"></script>

  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    #calendar { max-width: 900px; margin: 0 auto; }
  </style>
</head>
<body>

<h2 style="text-align: center;">🗓️ Calendar - Local FullCalendar</h2>
<div id='calendar'></div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 650
    });
    calendar.render();
  });
</script>

</body>
</html>
