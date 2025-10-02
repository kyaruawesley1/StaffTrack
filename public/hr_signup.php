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
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white shadow-lg rounded-lg p-8 w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">HR Signup</h2>
    
    <?php if($error): ?>
      <p class="text-red-500 mb-4 text-sm"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if($success): ?>
      <p class="text-green-500 mb-4 text-sm"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
      <div class="mb-4">
        <label class="block text-gray-700">Username</label>
        <input type="text" name="username" required class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-blue-300">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" required class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-blue-300">
      </div>
      <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">Sign Up</button>
    </form>
    <p class="mt-4 text-center text-sm">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
  </div>

</body>
</html>
