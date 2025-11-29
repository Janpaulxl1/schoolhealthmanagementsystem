<?php 
require 'db.php';

// Fetch all programs from DB
$programs = [];
$result = $conn->query("SELECT * FROM programs ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $programs[(int)$row['id']] = $row['name'];
    }
}

// Fetch all sections with semester + archive status
$sections = [];
$result = $conn->query("
    SELECT s.id, s.name, s.semester, s.is_archived, p.name AS program, p.id AS program_id
    FROM sections s
    JOIN programs p ON s.program_id = p.id
    ORDER BY p.name ASC, s.name ASC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pid = (int)$row['program_id'];
        if (!isset($sections[$pid])) $sections[$pid] = [];
        $sections[$pid][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>File Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-48 bg-gradient-to-b from-orange-500 to-red-800 text-white p-4">
    <h2><a href="nurse.php">&#8617; Go Back</a></h2>
    <nav class="space-y-14 font-lg">
      <?php foreach ($programs as $id => $program): ?>
        <div onclick='showProgram(<?= json_encode($program, JSON_UNESCAPED_UNICODE) ?>, <?= (int)$id ?>)'
             class="hover:bg-white/20 px-4 py-2 rounded cursor-pointer">
          <?= htmlspecialchars($program, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endforeach; ?>
      <div onclick='showArchive()' class="hover:bg-white/20 px-4 py-2 rounded cursor-pointer">
        📁 Archive
      </div>
      <?php if (empty($programs)): ?>
        <div class="text-white/80 text-sm">No programs found</div>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 bg-white p-6 overflow-y-auto">
    <h1 class="text-3xl font-bold text-center text-red-700 mb-6">STUDENT FILE DASHBOARD</h1>

    <!-- Filters & Add Folder -->
    <div id="filtersSection" class="flex flex-wrap justify-between gap-4 mb-6 px-4">
      <div class="relative">
        <input type="text" id="searchInput" onkeyup="handleSearch()" placeholder="Search..." class="w-64 px-4 py-2 pl-10 border rounded" />
        <span class="absolute left-3 top-2.5 text-gray-500"><i class="fas fa-search"></i></span>
      </div>
      <button id="addFolderBtn" onclick="openAddFolderModal()" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Add Folder</button>
    </div>

    <!-- Folder View -->
    <div id="folderView">
      <div id="programTitle" class="text-xl font-bold text-center mb-8"></div>
      <div id="folders" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 justify-items-center"></div>
    </div>

    <!-- Student View -->
    <div id="studentView" class="hidden">
      <h2 id="classTitle" class="text-2xl font-semibold text-center text-gray-800 mb-6"></h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
          <thead>
            <tr class="bg-red-700 text-white">
              <th class="py-2 px-4 border">No.</th>
              <th class="py-2 px-4 border">Student ID</th>
              <th class="py-2 px-4 border">Name</th>
              <th class="py-2 px-4 border">Phone</th>
              <th class="py-2 px-4 border">Email</th>
              <th class="py-2 px-4 border">Requirements</th>
              <th class="py-2 px-4 border">Emergency Contact</th>
              <th class="py-2 px-4 border">Appointment History</th>
            </tr>
          </thead>
          <tbody id="studentTable"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Add Folder Modal -->
<div id="addFolderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded shadow-lg w-80">
    <h2 class="text-lg font-semibold mb-4">Add Folder</h2>
    <input type="text" id="newFolderName" placeholder="Folder Name" class="w-full px-4 py-2 border rounded mb-2">
    <select id="newSemester" class="w-full px-4 py-2 border rounded mb-2">
      <option value="1st Semester">1st Semester</option>
      <option value="2nd Semester">2nd Semester</option>
      <option value="Summer">Summer</option>
    </select>
    <label class="flex items-center space-x-2 mb-4">
      <input type="checkbox" id="newIsArchived"> <span>Archive</span>
    </label>
    <div class="flex justify-end space-x-2">
      <button onclick="closeAddFolderModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
      <button onclick="addFolder()" class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800">Add</button>
    </div>
  </div>
</div>

<script>
let currentProgram = null;
let currentProgramId = null;
let currentSectionId = null;

// Sections & programs from PHP → JS
const sections = <?= json_encode($sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const programs = <?= json_encode($programs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

function showProgram(programName, programId) {
  currentProgram = programName;
  currentProgramId = String(programId);
  document.getElementById('programTitle').innerText = programName || '';
  document.getElementById('folderView').classList.remove('hidden');
  document.getElementById('studentView').classList.add('hidden');
  document.getElementById('addFolderBtn').classList.remove('hidden');
  filterFolders();
}

function showArchive() {
  currentProgram = "Archive";
  currentProgramId = "archive";
  document.getElementById('programTitle').innerText = "Archive";
  document.getElementById('folderView').classList.remove('hidden');
  document.getElementById('studentView').classList.add('hidden');
  document.getElementById('addFolderBtn').classList.add('hidden');
  filterFolders();
}

// Handle search for both views
function handleSearch() {
  if (!document.getElementById('folderView').classList.contains('hidden')) {
    filterFolders(); // folder search
  } else {
    filterStudents(); // student search
  }
}

// Filter and display folders
function filterFolders() {
  const container = document.getElementById('folders');
  const search = (document.getElementById('searchInput').value || '').toLowerCase();

  container.innerHTML = '';
  let list = [];
  if (currentProgramId === "archive") {
    // Collect all archived folders from all programs
    Object.values(sections).forEach(programSections => {
      programSections.forEach(folder => {
        if (Number(folder.is_archived) === 1) {
          list.push(folder);
        }
      });
    });
  } else {
    list = sections[currentProgramId] || [];
  }

  list.forEach(folder => {
    const name = folder.name || folder.section_name || '';
    const semester = folder.semester || '';
    const isArchived = Number(folder.is_archived) === 1 ? 1 : 0;

    let show = name.toLowerCase().includes(search);

    if (show) {
      // If viewing a program (not archive) and folder is archived, skip showing it
      if (currentProgramId !== "archive" && isArchived === 1) {
        return; // skip this folder so it disappears from program folder list when archived
      }

      const div = document.createElement('div');
      const folderColor = isArchived ? 'bg-gray-400' : 'bg-yellow-400';
      const textExtra = isArchived ? ' (Archived)' : '';
      div.className = "text-center group";

      div.innerHTML = `
        <div class="relative w-28 h-20 ${folderColor} rounded-b-md shadow-md group-hover:brightness-110 cursor-pointer"></div>
        <div class="text-sm font-medium mt-2">${name} - ${semester}${textExtra}</div>
        <button onclick="toggleArchive(${folder.id}, ${isArchived})" class="mt-2 px-2 py-1 text-xs rounded ${isArchived ? 'bg-green-600 text-white' : 'bg-gray-600 text-white'}">
          ${isArchived ? 'Unarchive' : 'Archive'}
        </button>
      `;

      div.onclick = (e) => {
        if (!e.target.closest('button')) {
          openFolder(folder.id, `${name} - ${semester}`);
        }
      };

      container.appendChild(div);
    }
  });
}

// Archive / Unarchive folder
function toggleArchive(sectionId, currentStatus) {
  fetch('archive_folder.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ section_id: sectionId, is_archived: currentStatus ? 0 : 1 })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      location.reload();
    } else {
      alert(data.message || 'Update failed');
    }
  })
  .catch(err => console.error(err));
}

// Add folder modal
function openAddFolderModal() { document.getElementById('addFolderModal').classList.remove('hidden'); }
function closeAddFolderModal() { 
  document.getElementById('addFolderModal').classList.add('hidden');
  document.getElementById('newFolderName').value = '';
  document.getElementById('newSemester').value = '1st Semester';
  document.getElementById('newIsArchived').checked = false;
}

// Add folder via PHP
function addFolder() {
  const name = (document.getElementById('newFolderName').value || '').trim();
  const semester = document.getElementById('newSemester').value;
  const isArchived = document.getElementById('newIsArchived').checked ? 1 : 0;

  if (!currentProgramId || !name || !semester) {
    alert("Missing required fields");
    return;
  }

  fetch('add_folder.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      program_id: Number(currentProgramId),
      name: name,
      semester: semester,
      is_archived: isArchived
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert(data.message || 'Folder added');
      closeAddFolderModal();
      location.reload();
    } else {
      alert(data.message || 'Add failed');
    }
  })
  .catch(err => console.error(err));
}

// Open folder to view students
function openFolder(sectionId, sectionName) {
  currentSectionId = sectionId;
  document.getElementById('folderView').classList.add('hidden');
  document.getElementById('studentView').classList.remove('hidden');
  document.getElementById('classTitle').innerText = sectionName || '';
  document.getElementById('addFolderBtn').classList.add('hidden');
  loadStudentList();
}

// Normalize helper
function pick(...args) { for (const a of args) { if (a !== undefined && a !== null && a !== '') return a; } return ''; }

// Load students in section
function loadStudentList() {
  fetch(`studentlist.php?section_id=${currentSectionId}&json=1`)
  .then(res => res.json())
  .then(data => {
    const table = document.getElementById('studentTable');
    table.innerHTML = '';
    const rows = (data && (data.students || data.data || [])) || [];

    rows.forEach((student, i) => {
      const id = pick(student.student_id, student.studentID, student.id_number, student.student_no, student.sid);
      const displayName = pick(
        student.name,
        student.full_name,
        [student.first_name, student.middle_name, student.last_name].filter(Boolean).join(' ').trim(),
        [student.firstname, student.middlename, student.lastname].filter(Boolean).join(' ').trim(),
        student.student_name
      );
      const phone = pick(student.phone, student.contact, student.contact_number, student.mobile, student.cp_number);
      const email = pick(student.email, student.email_address);
      const emergency = pick(student.emergency_contact, student.guardian_name, student.parent_contact);

      // create small inline icons so label + icon are vertically centered
      const xrayIcon = student.xray ? '<span class="inline-flex items-center justify-center w-6 h-6 text-green-600 text-base leading-none">✅</span>' : '<span class="inline-flex items-center justify-center w-6 h-6 text-red-600 text-base leading-none">❌</span>';
      const medIcon  = student.medical_history ? '<span class="inline-flex items-center justify-center w-6 h-6 text-green-600 text-base leading-none">✅</span>' : '<span class="inline-flex items-center justify-center w-6 h-6 text-red-600 text-base leading-none">❌</span>';
      const labIcon  = student.laboratory_results ? '<span class="inline-flex items-center justify-center w-6 h-6 text-green-600 text-base leading-none">✅</span>' : '<span class="inline-flex items-center justify-center w-6 h-6 text-red-600 text-base leading-none">❌</span>';

      // Requirements formatted using flex rows to keep icon inline with text
      const requirementsHTML = `
        <div class="flex flex-col gap-1 text-sm">
          <div class="flex items-center gap-2">
            <span class="w-36 font-medium text-sm">X-ray</span>
            ${xrayIcon}
          </div>
          <div class="flex items-center gap-2">
            <span class="w-36 font-medium text-sm">Medical History</span>
            ${medIcon}
          </div>
          <div class="flex items-center gap-2">
            <span class="w-36 font-medium text-sm">Lab Results</span>
            ${labIcon}
          </div>
        </div>
      `;

      const tr = document.createElement('tr');
      tr.className = 'hover:bg-gray-100 align-top';
      tr.innerHTML = `
        <td class="py-2 px-4 border align-top">${i + 1}</td>
        <td class="py-2 px-4 border align-top">${id || ''}</td>
        <td class="py-2 px-4 border align-top">${displayName || ''}</td>
        <td class="py-2 px-4 border align-top">${phone || ''}</td>
        <td class="py-2 px-4 border align-top">${email || ''}</td>
        <td class="py-2 px-4 border align-top">${requirementsHTML}</td>
        <td class="py-2 px-4 border align-top">${emergency || ''}</td>
        <td class="py-2 px-4 border align-top"><a href="appointment_history.php?student_id=${encodeURIComponent(id)}" class="text-blue-600 hover:underline">View</a></td>
      `;
      table.appendChild(tr);
    });
  })
  .catch(err => {
    console.error('studentlist error', err);
    document.getElementById('studentTable').innerHTML = '<tr><td class="py-2 px-4 border text-red-700" colspan="8">Failed to load students</td></tr>';
  });
}

// Filter students in student view
function filterStudents() {
  const search = (document.getElementById('searchInput').value || '').toLowerCase();
  const rows = document.querySelectorAll("#studentTable tr");

  rows.forEach(row => {
    const cells = row.querySelectorAll("td");
    if (cells.length < 5) return;

    const id = cells[1].textContent.toLowerCase();
    const name = cells[2].textContent.toLowerCase();
    const phone = cells[3].textContent.toLowerCase();
    const email = cells[4].textContent.toLowerCase();

    if (id.includes(search) || name.includes(search) || phone.includes(search) || email.includes(search)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

window.onload = () => { 
  const ids = Object.keys(programs || {});
  if (ids.length > 0) {
    const firstProgramId = ids[0];
    const firstProgramName = programs[firstProgramId];
    showProgram(firstProgramName, firstProgramId);
  } else {
    document.getElementById('programTitle').innerText = 'No programs available';
    document.getElementById('addFolderBtn').classList.add('hidden');
  }
};
</script>
</body>
</html>
