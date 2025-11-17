<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    header("Location: ../public/login.php");
    exit;
}

// Fetch all employees grouped by department
$departments_stmt = $pdo->prepare("SELECT * FROM departments ORDER BY name ASC");
$departments_stmt->execute();
$departments = $departments_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch employees for each department
$employees_by_department = [];
foreach ($departments as $dept) {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE department_id = ? ORDER BY first_name ASC");
    $stmt->execute([$dept['id']]);
    $employees_by_department[$dept['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Department Dashboard - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Department Dashboard</h1>
<ul class="flex space-x-4">
<li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-2xl font-bold mb-6">Welcome, <?= htmlspecialchars($username); ?> (Department Head)</h2>
<h2 class="text-2xl font-bold mb-6">Employees by Department</h2>

<?php foreach ($departments as $dept): ?>
<div class="mb-8">
<h3 class="text-xl font-semibold mb-3 text-red-600"><?= htmlspecialchars($dept['name']) ?></h3>
<?php if (!empty($employees_by_department[$dept['id']])): ?>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Employee Name</th>
<th class="border px-4 py-2">Email</th>
<th class="border px-4 py-2">Phone</th>
<th class="border px-4 py-2">Role</th>
<th class="border px-4 py-2">Date Joined</th>
</tr>
</thead>
<tbody>
<?php foreach($employees_by_department[$dept['id']] as $emp): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['email']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['phone']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['role']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($emp['date_joined']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php else: ?>
<p class="text-gray-500">No employees in this department.</p>
<?php endif; ?>
</div>
<?php endforeach; ?>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
