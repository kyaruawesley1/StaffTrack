<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];
$success = "";
$error = "";

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = trim($_POST['reason']);

    // Validation: leave must be requested at least 7 days in advance
    if (strtotime($start_date) < strtotime("+7 days")) {
        $error = "Leave requests must be submitted at least 7 days in advance.";
    } elseif (!$start_date || !$end_date || !$reason) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$employee_id, $start_date, $end_date, $reason])) {
            $success = "Leave request submitted successfully!";
        } else {
            $error = "Failed to submit leave request.";
        }
    }
}

// Fetch employee leave requests
$stmt = $pdo->prepare("SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY start_date DESC");
$stmt->execute([$employee_id]);
$leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Leave Requests - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Leave Requests</h1>
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
<?php if($error): ?><p class="bg-red-50 text-red-600 p-2 mb-4 rounded"><?= $error ?></p><?php endif; ?>
<?php if($success): ?><p class="bg-green-50 text-green-600 p-2 mb-4 rounded"><?= $success ?></p><?php endif; ?>

<div class="bg-white p-6 rounded-xl shadow-md mb-8">
<h2 class="text-xl font-bold mb-4">Submit a Leave Request</h2>
<form method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-gray-700 mb-1">Start Date</label>
        <input type="date" name="start_date" required class="w-full p-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-gray-700 mb-1">End Date</label>
        <input type="date" name="end_date" required class="w-full p-2 border rounded-lg">
    </div>
    <div class="col-span-full">
        <label class="block text-gray-700 mb-1">Reason</label>
        <textarea name="reason" required class="w-full p-2 border rounded-lg"></textarea>
    </div>
    <div class="col-span-full flex justify-end">
        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Submit</button>
    </div>
</form>
</div>

<h2 class="text-xl font-bold mb-4">My Leave Requests</h2>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Start Date</th>
<th class="border px-4 py-2">End Date</th>
<th class="border px-4 py-2">Reason</th>
<th class="border px-4 py-2">Status</th>
</tr>
</thead>
<tbody>
<?php if($leaves): ?>
<?php foreach($leaves as $leave): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= $leave['start_date'] ?></td>
<td class="border px-4 py-2"><?= $leave['end_date'] ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($leave['reason']) ?></td>
<td class="border px-4 py-2"><?= $leave['status'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="4" class="border px-4 py-2 text-gray-500">No leave requests found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>
</body>
</html>
