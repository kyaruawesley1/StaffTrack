<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../public/login.php");
    exit;
}

// Fetch all employees with department name
$stmt = $pdo->prepare("
    SELECT e.id, e.first_name, e.last_name, e.email, e.phone, e.role, d.name AS department
    FROM employees e
    JOIN departments d ON e.department_id = d.id
    ORDER BY e.first_name ASC
");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Employees - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">All Employees</h1>
<ul class="flex space-x-4">
    <li><a href="manage_employees.php" class="text-gray-700 hover:text-red-600 font-medium">Employees</a></li>
    <li><a href="department_reports.php" class="text-gray-700 hover:text-red-600 font-medium">Departments</a></li>
    <li><a href="attendance_reports.php" class="text-gray-700 hover:text-red-600 font-medium">Attendance</a></li>
    <li><a href="leave_requests.php" class="text-gray-700 hover:text-red-600 font-medium">Leave Requests</a></li>
    <li><a href="performance_reviews.php" class="text-gray-700 hover:text-red-600 font-medium">Performance</a></li>
    <li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<div class="bg-white shadow-md rounded-xl p-6 overflow-x-auto">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Name</th>
<th class="border px-4 py-2">Email</th>
<th class="border px-4 py-2">Phone</th>
<th class="border px-4 py-2">Role</th>
<th class="border px-4 py-2">Department</th>
</tr>
</thead>
<tbody>
<?php if($employees): ?>
<?php foreach($employees as $emp): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['email']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['phone']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['role']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['department']) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="5" class="border px-4 py-2 text-gray-500">No employees found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm mt-10">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
