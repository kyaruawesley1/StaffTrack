<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    header("Location: ../public/login.php");
    exit;
}

$reviewer_id = $_SESSION['user_id'];
$employee_id = $_GET['employee_id'] ?? null;

if (!$employee_id) {
    header("Location: dashboard.php");
    exit;
}

// Check that the employee belongs to the department head's department
$stmt = $pdo->prepare("SELECT department_id FROM employees WHERE id = ?");
$stmt->execute([$reviewer_id]);
$dept = $stmt->fetch(PDO::FETCH_ASSOC);
$department_id = $dept['department_id'] ?? null;

$emp_stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ? AND department_id = ?");
$emp_stmt->execute([$employee_id, $department_id]);
$employee = $emp_stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("You cannot review this employee.");
}

$success = "";
$error = "";

// Submit review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_date = $_POST['review_date'];
    $feedback = trim($_POST['feedback']);
    $rating = (int)$_POST['rating'];

    if (!$review_date || !$feedback || !$rating) {
        $error = "All fields are required.";
    } else if ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } else {
        $insert = $pdo->prepare("INSERT INTO performance_reviews (employee_id, review_date, reviewer_id, feedback, rating) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$employee_id, $review_date, $reviewer_id, $feedback, $rating]);
        $success = "Performance review submitted successfully!";
    }
}

// Fetch existing reviews for the employee
$reviews_stmt = $pdo->prepare("
    SELECT pr.review_date, u.username AS reviewer, pr.feedback, pr.rating
    FROM performance_reviews pr
    JOIN users u ON pr.reviewer_id = u.id
    WHERE pr.employee_id = ?
    ORDER BY pr.review_date DESC
");
$reviews_stmt->execute([$employee_id]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Performance Review - <?= htmlspecialchars($employee['first_name'].' '.$employee['last_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Performance Review</h1>
<ul class="flex space-x-4">
<li><a href="dashboard.php" class="text-gray-700 hover:text-red-600 font-medium">Dashboard</a></li>
<li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-2xl font-bold mb-6"><?= htmlspecialchars($employee['first_name'].' '.$employee['last_name']) ?></h2>

<?php if($error): ?>
<p class="bg-red-50 text-red-600 border border-red-200 p-3 rounded mb-4"><?= $error ?></p>
<?php endif; ?>
<?php if($success): ?>
<p class="bg-green-50 text-green-600 border border-green-200 p-3 rounded mb-4"><?= $success ?></p>
<?php endif; ?>

<div class="bg-white shadow-md rounded-xl p-6 mb-8">
<h3 class="text-xl font-bold mb-4">Submit Review</h3>
<form method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div>
<label class="block text-gray-700 mb-1">Review Date</label>
<input type="date" name="review_date" required class="w-full p-3 border rounded-lg">
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
<div class="col-span-full">
<label class="block text-gray-700 mb-1">Feedback</label>
<textarea name="feedback" required class="w-full p-3 border rounded-lg"></textarea>
</div>
<div class="col-span-full flex justify-end">
<button type="submit" class="bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600">Submit Review</button>
</div>
</form>
</div>

<h3 class="text-xl font-bold mb-4">Existing Reviews</h3>
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
<?php foreach($reviews as $r): ?>
<tr class="text-center">
<td class="border px-4 py-2"><?= $r['review_date'] ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($r['reviewer']) ?></td>
<td class="border px-4 py-2"><?= htmlspecialchars($r['feedback']) ?></td>
<td class="border px-4 py-2"><?= $r['rating'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="4" class="border px-4 py-2 text-gray-500">No reviews found.</td></tr>
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
