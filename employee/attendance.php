<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch attendance records
$stmt = $pdo->prepare("SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC");
$stmt->execute([$employee_id]);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Attendance</h1>
<ul class="flex space-x-4">
<li><a href="dashboard.php" class="text-gray-700 hover:text-red-600 font-medium">Profile</a></li>
<li><a href="leave.php" class="text-gray-700 hover:text-red-600 font-medium">Leave Requests</a></li>
<li><a href="attendance.php" class="text-gray-700 hover:text-red-600 font-medium">Attendance</a></li>
<li><a href="performance.php" class="text-gray-700 hover:text-red-600 font-medium">Performance</a></li>
<li><a href="logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-xl font-bold mb-4">My Attendance Records</h2>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Date</th>
<th class="border px-4 py-2">Status</th>
</tr>
</thead>
<tbody>
<?php if($attendance): ?>
<?php foreach($attendance as $att): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= $att['date'] ?></td>
<td class="border px-4 py-2"><?= $att['status'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="2" class="border px-4 py-2 text-gray-500">No attendance records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>
</body>
</html>
