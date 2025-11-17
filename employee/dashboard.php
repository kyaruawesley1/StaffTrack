<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee info
$stmt = $pdo->prepare("SELECT e.*, d.name AS department_name 
                       FROM employees e 
                       JOIN departments d ON e.department_id = d.id
                       WHERE e.id = ?");
$stmt->execute([$employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    echo "Employee record not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Dashboard - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Employee Dashboard</h1>
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
<h2 class="text-2xl font-bold mb-6">My Profile</h2>
<div class="bg-white p-6 rounded-xl shadow-md w-full max-w-xl">
<p><strong>Employee ID:</strong> <?= htmlspecialchars($employee['id']) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($employee['first_name'].' '.$employee['last_name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($employee['phone']) ?></p>
<p><strong>Department:</strong> <?= htmlspecialchars($employee['department_name']) ?></p>
<p><strong>Role:</strong> <?= htmlspecialchars($employee['role']) ?></p>
<p><strong>Date Joined:</strong> <?= htmlspecialchars($employee['date_joined']) ?></p>
</div>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
