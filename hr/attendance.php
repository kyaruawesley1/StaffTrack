<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR') {
    header("Location: ../public/login.php");
    exit;
}

// Fetch employees
$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM employees ORDER BY first_name ASC");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = "";
$error = "";

// Handle attendance submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    if (!$employee_id || !$date || !$status) {
        $error = "All fields are required.";
    } else {
        // Check if attendance exists
        $check = $pdo->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
        $check->execute([$employee_id, $date]);
        if ($check->rowCount() > 0) {
            $update = $pdo->prepare("UPDATE attendance SET status = ? WHERE employee_id = ? AND date = ?");
            $update->execute([$status, $employee_id, $date]);
        } else {
            $insert = $pdo->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)");
            $insert->execute([$employee_id, $date, $status]);
        }
        $success = "Attendance recorded successfully!";
    }
}

// Fetch attendance records
$att_stmt = $pdo->prepare("
    SELECT a.id, e.first_name, e.last_name, a.date, a.status
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    ORDER BY a.date DESC
");
$att_stmt->execute();
$attendance_records = $att_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Manage Attendance</h1>
<ul class="flex space-x-4">
<li><a href="dashboard.php" class="text-gray-700 hover:text-red-600 font-medium">Dashboard</a></li>
<li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<?php if($error): ?>
<p class="bg-red-50 text-red-600 border border-red-200 p-3 rounded mb-4"><?= $error ?></p>
<?php endif; ?>
<?php if($success): ?>
<p class="bg-green-50 text-green-600 border border-green-200 p-3 rounded mb-4"><?= $success ?></p>
<?php endif; ?>

<div class="bg-white shadow-md rounded-xl p-6 mb-8">
<h2 class="text-2xl font-bold mb-4">Record Attendance</h2>
<form method="post" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<div>
<label class="block text-gray-700 mb-1">Employee</label>
<select name="employee_id" required class="w-full p-3 border rounded-lg">
<option value="">Select Employee</option>
<?php foreach($employees as $emp): ?>
<option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div>
<label class="block text-gray-700 mb-1">Date</label>
<input type="date" name="date" required class="w-full p-3 border rounded-lg">
</div>
<div>
<label class="block text-gray-700 mb-1">Status</label>
<select name="status" required class="w-full p-3 border rounded-lg">
<option value="Present">Present</option>
<option value="Absent">Absent</option>
<option value="Late">Late</option>
</select>
</div>
<div class="flex items-end">
<button type="submit" class="bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600 w-full">Record</button>
</div>
</form>
</div>

<h2 class="text-2xl font-bold mb-4">Attendance Records</h2>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Employee</th>
<th class="border px-4 py-2">Date</th>
<th class="border px-4 py-2">Status</th>
</tr>
</thead>
<tbody>
<?php if($attendance_records): ?>
<?php foreach($attendance_records as $a): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= htmlspecialchars($a['first_name'].' '.$a['last_name']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($a['date']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($a['status']) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="3" class="border px-4 py-2 text-gray-500">No records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
