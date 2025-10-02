<?php
require_once __DIR__ . "/../config.php";

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - StaffTrack</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="sales.css">
</head>
<body class="bg-gray-100">
  <header class="navbar p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">StaffTrack Dashboard</h1>
    <a href="logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
  </header>

  <main class="p-6 fade-in">
    <h2 class="text-2xl font-semibold mb-4">Welcome, <?= htmlspecialchars($user['username']); ?> (<?= $user['role']; ?>)</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php if($user['role'] == "HR"): ?>
        <a href="create_employee.php" class="card bg-white p-6 rounded shadow hover:bg-blue-50">
          <h3 class="font-bold">Create Employee</h3>
        </a>
      <?php endif; ?>
      <a href="employee_profile.php" class="card bg-white p-6 rounded shadow hover:bg-blue-50">
        <h3 class="font-bold">My Profile</h3>
      </a>
    </div>
  </main>
</body>
</html>
