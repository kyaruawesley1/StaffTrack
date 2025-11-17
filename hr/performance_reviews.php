<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR') {
    header("Location: ../public/login.php");
    exit;
}

// Fetch employees for review from employees table
$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM employees ORDER BY first_name ASC");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = "";
$error = "";

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $reviewer_id = $_SESSION['user_id']; // HR user ID
    $comment = trim($_POST['feedback']);
    $rating = (int)$_POST['rating'];

    if (!$employee_id || !$comment || !$rating) {
        $error = "All fields are required.";
    } else if ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } else {
        // Insert review; review_date auto-set
        $insert = $pdo->prepare("INSERT INTO performance_reviews (employee_id, reviewed_by, comment, rating) VALUES (?, ?, ?, ?)");
        $insert->execute([$employee_id, $reviewer_id, $comment, $rating]);
        $success = "Performance review submitted successfully!";
    }
}

// Fetch all reviews and join with employees and users for names
$reviews_stmt = $pdo->prepare("
    SELECT pr.id, e.first_name, e.last_name, u.username AS reviewer, pr.review_date, pr.comment, pr.rating
    FROM performance_reviews pr
    JOIN employees e ON pr.employee_id = e.id
    JOIN users u ON pr.reviewed_by = u.id
    ORDER BY pr.review_date DESC
");
$reviews_stmt->execute();
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Performance Reviews - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Performance Reviews</h1>
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
<h2 class="text-2xl font-bold mb-4">Submit Performance Review</h2>
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
<label class="block text-gray-700 mb-1">Feedback</label>
<textarea name="feedback" required class="w-full p-3 border rounded-lg"></textarea>
</div>
<div>
<label class="block text-gray-700 mb-1">Rating</label>
<select name="rating" required class="w-full p-3 border rounded-lg">
<option value="">Select Rating</option>
<?php for($i=1;$i<=5;$i++): ?>
<option value="<?= $i ?>"><?= $i ?></option>
<?php endfor; ?>
</select>
</div>
<div class="col-span-full flex justify-end">
<button type="submit" class="bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600">Submit</button>
</div>
</form>
</div>

<h2 class="text-2xl font-bold mb-4">All Performance Reviews</h2>
<div class="overflow-x-auto bg-white shadow-md rounded-xl p-4">
<table class="min-w-full table-auto border-collapse border border-gray-200">
<thead>
<tr class="bg-red-100 text-center">
<th class="border px-4 py-2">Employee</th>
<th class="border px-4 py-2">Review Date</th>
<th class="border px-4 py-2">Reviewer</th>
<th class="border px-4 py-2">Comment</th>
<th class="border px-4 py-2">Rating</th>
</tr>
</thead>
<tbody>
<?php if($reviews): ?>
<?php foreach($reviews as $r): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></td>
<td class="border px-4 py-2"><?= $r['review_date'] ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($r['reviewer']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($r['comment']) ?></td>
<td class="border px-4 py-2"><?= $r['rating'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="5" class="border px-4 py-2 text-gray-500">No performance reviews found.</td></tr>
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
