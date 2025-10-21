<?php
require_once __DIR__ . "/../config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = "HR"; // only HR signup allowed

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        $success = "HR account created successfully! You can now log in.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Username already exists.";
        } else {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HR Signup - StaffTrack</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-100 via-white to-green-200 min-h-screen flex flex-col items-center justify-center font-sans">

  <!-- Header -->
  <div class="text-center mb-8">
    <h1 class="text-4xl font-extrabold text-red-600 tracking-tight">MetroWorks Enterprises StaffTrack</h1>
    <p class="text-gray-600 mt-2">HUMAN RESOURCE ACCOUNT</p>
  </div>

  <!-- Signup Card -->
  <div class="bg-white shadow-2xl rounded-2xl p-8 w-96 border-t-4 border-red-500 transition transform hover:scale-[1.02] duration-300">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">HR Signup</h2>

    <?php if($error): ?>
      <p class="text-red-500 mb-4 text-center text-sm font-semibold bg-red-50 p-2 rounded"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if($success): ?>
      <p class="text-red-600 mb-4 text-center text-sm font-semibold bg-red-50 p-2 rounded"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" class="space-y-5">
      <div>
        <label class="block text-gray-700 font-medium mb-1">Username</label>
        <input type="text" name="username" required 
               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition">
      </div>
      <div>
        <label class="block text-gray-700 font-medium mb-1">Password</label>
        <input type="password" name="password" required 
               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 transition">
      </div>

      <button type="submit" 
              class="w-full bg-red-500 text-white py-3 rounded-lg font-semibold text-lg shadow-md hover:bg-red-600 hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5">
        Sign Up
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Already have an account?
      <a href="login.php" class="text-red-600 font-medium hover:underline">Login here</a>
    </p>
  </div>

  <!-- Footer -->
  <footer class="mt-10 text-gray-500 text-xs">
    &copy; <?= date("Y") ?> MetroWorks Enterprises StaffTrack. All rights reserved.
  </footer>

</body>
</html>
<?php