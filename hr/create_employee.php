<?php
session_start();
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR') {
    header("Location: ../public/login.php");
    exit;
}

$error = "";
$success = "";

// Fetch departments
$dept_stmt = $pdo->prepare("SELECT * FROM departments ORDER BY name ASC");
$dept_stmt->execute();
$departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];
    $date_joined = $_POST['date_joined'];

    if (!$first_name || !$last_name || !$email || !$department_id || !$role || !$date_joined) {
        $error = "All fields except phone are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO employees 
                (first_name, last_name, email, phone, department_id, role, date_joined)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $email, $phone, $department_id, $role, $date_joined]);
            $success = "Employee created successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already exists.";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Employee - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<header class="bg-white shadow-md py-4">
<nav class="max-w-6xl mx-auto flex justify-between items-center px-6">
<h1 class="text-2xl font-bold text-red-600">Create Employee</h1>
<ul class="flex space-x-4">
<li><a href="dashboard.php" class="text-gray-700 hover:text-red-600 font-medium">Dashboard</a></li>
<li><a href="../public/logout.php" class="text-gray-700 hover:text-red-600 font-medium">Logout</a></li>
</ul>
</nav>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
<div class="bg-white shadow-md rounded-xl p-6">

<?php if($error): ?>
<p class="bg-red-50 text-red-600 border border-red-200 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if($success): ?>
<p class="bg-green-50 text-green-600 border border-green-200 p-3 rounded mb-4"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post" class="space-y-4">
<div>
<label class="block text-gray-700 mb-1">First Name</label>
<input type="text" name="first_name" required class="w-full p-3 border rounded-lg">
</div>
<div>
<label class="block text-gray-700 mb-1">Last Name</label>
<input type="text" name="last_name" required class="w-full p-3 border rounded-lg">
</div>
<div>
<label class="block text-gray-700 mb-1">Email</label>
<input type="email" name="email" required class="w-full p-3 border rounded-lg">
</div>
<div>
<label class="block text-gray-700 mb-1">Phone</label>
<input type="text" name="phone" class="w-full p-3 border rounded-lg">
</div>
<div>
<label class="block text-gray-700 mb-1">Department</label>
<select name="department_id" required class="w-full p-3 border rounded-lg">
<option value="">Select Department</option>
<?php foreach($departments as $dept): ?>
<option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div>
<label class="block text-gray-700 mb-1">Role</label>
<select name="role" required class="w-full p-3 border rounded-lg">
<option value="">Select Role</option>
<option value="Employee">Employee</option>
<option value="Department Head">Department Head</option>
</select>
</div>
<div>
<label class="block text-gray-700 mb-1">Date Joined</label>
<input type="date" name="date_joined" required class="w-full p-3 border rounded-lg">
</div>
<button type="submit" class="bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600">Create Employee</button>
</form>
</div>
</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm">
&copy; <?= date("Y"); ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
