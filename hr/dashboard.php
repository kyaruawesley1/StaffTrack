<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HR Dashboard - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">HR Dashboard</h1>
<ul class="flex space-x-4">
<li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-2xl font-bold mb-6">Welcome, <?= htmlspecialchars($username); ?> (<?= htmlspecialchars($role); ?>)</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="create_employee.php" class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
        <h3 class="text-xl font-bold text-red-600 mb-2">Add Employee</h3>
        <p class="text-gray-600">Create new employee records.</p>
    </a>
    <a href="employee_list.php" class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
        <h3 class="text-xl font-bold text-red-600 mb-2">Employee List</h3>
        <p class="text-gray-600">View all employees and their details.</p>
    </a>
    <a href="attendance.php" class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
        <h3 class="text-xl font-bold text-red-600 mb-2">Attendance</h3>
        <p class="text-gray-600">Record and view employee attendance.</p>
    </a>
    <a href="leave_requests.php" class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
        <h3 class="text-xl font-bold text-red-600 mb-2">Leave Requests</h3>
        <p class="text-gray-600">Submit and manage leave requests.</p>
    </a>
    <a href="performance_reviews.php" class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
        <h3 class="text-xl font-bold text-red-600 mb-2">Performance Reviews</h3>
        <p class="text-gray-600">Submit and view employee performance reviews.</p>
    </a>
</div>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm mt-10">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
