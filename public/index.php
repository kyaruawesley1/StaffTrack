<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StaffTrack - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

  <header class="bg-blue-600 p-4 text-white shadow-md flex justify-between items-center">
    <h1 class="text-2xl font-bold">MetroWorks EnterprisesStaffTrack</h1>
    <nav>
      <?php if(isset($_SESSION['user'])): ?>
        <a href="logout.php" class="px-4 py-2 bg-red-500 rounded hover:bg-red-600 transition">Logout</a>
      <?php else: ?>
        <a href="login.php" class="px-4 py-2 bg-green-500 rounded hover:bg-green-600 transition">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="p-8 text-center">
    <h2 class="text-3xl font-bold mb-4">Welcome to MetroWorks Enterprises StaffTrack</h2>
    <p class="text-gray-700 mb-6">Your digital employee management system for record-keeping, attendance tracking, and performance monitoring.</p>
    <a href="login.php" class="px-6 py-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Get Started</a>
  </main>

</body>
</html>
