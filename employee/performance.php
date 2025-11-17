<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch performance reviews
$stmt = $pdo->prepare("
    SELECT pr.*, u.username AS reviewer
    FROM performance_reviews pr
    JOIN users u ON pr.reviewed_by = u.id
    WHERE pr.employee_id = ?
    ORDER BY pr.review_date DESC
");
$stmt->execute([$employee_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Performance Reviews - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Performance Reviews</h1>
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
<h2 class="text-xl font-bold mb-4">My Performance Reviews</h2>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Review Date</th>
<th class="border px-4 py-2">Reviewer</th>
<th class="border px-4 py-2">Feedback</th>
<th class="border px-4 py-2">Rating</th>
</tr>
</thead>
<tbody>
<?php if($reviews): ?>
<?php foreach($reviews as $rev): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= $rev['review_date'] ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($rev['reviewer']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($rev['comment']) ?></td>
<td class="border px-4 py-2"><?= $rev['rating'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="4" class="border px-4 py-2 text-gray-500">No performance reviews found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>
</body>
</html>
