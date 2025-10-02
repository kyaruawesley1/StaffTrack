<?php
require_once __DIR__ . "/../config.php";

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? $_SESSION['user']['id'];
$empStmt = $pdo->prepare("SELECT e.*, d.name as dept_name FROM employees e JOIN departments d ON e.department_id=d.id WHERE e.id=?");
$empStmt->execute([$id]);
$employee = $empStmt->fetch();

if(!$employee) die("Employee not found.");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Employee Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="bg-white shadow rounded p-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4">Employee Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($employee['phone']) ?></p>
    <p><strong>Department:</strong> <?= htmlspecialchars($employee['dept_name']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($employee['role']) ?></p>
    <p><strong>Date Joined:</strong> <?= htmlspecialchars($employee['date_joined']) ?></p>
    <?php if($_SESSION['user']['role']=="HR"): ?>
      <a href="edit_employee.php?id=<?= $employee['id'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">Edit</a>
    <?php endif; ?>
  </div>
</body>
</html>
