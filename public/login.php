<?php
require_once __DIR__ . "/../config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $success = "Login successful! Redirecting...";

            header("refresh:2;url=dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - StaffTrack</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-100 via-white to-red-200 min-h-screen flex flex-col items-center justify-center font-sans">

  <!-- Header -->
  <div class="text-center mb-8">
    <h1 class="text-4xl font-extrabold text-red-600 tracking-tight">MetroWorks Enterprises StaffTrack</h1>
    <p class="text-gray-600 mt-2 text-sm">Welcome back!!! Please log in to continue</p>
  </div>

  <!-- Login Card -->
  <div class="bg-white shadow-2xl rounded-2xl p-8 w-96 border-t-4 border-red-500 transition transform hover:scale-[1.02] duration-300">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

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
               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 transition">
      </div>
      <div>
        <label class="block text-gray-700 font-medium mb-1">Password</label>
        <input type="password" name="password" required 
               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 transition">
      </div>

      <button type="submit" 
              class="w-full bg-red-500 text-white py-3 rounded-lg font-semibold text-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5">
        Log In
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don’t have an account?
      <a href="signup.php" class="text-red-600 font-medium hover:underline">Create one</a>
    </p>
  </div>

  <!-- Footer -->
  <footer class="mt-10 text-gray-500 text-xs">
    &copy; <?= date("Y") ?> MetroWorks Enterprises StaffTrack. All rights reserved.
  </footer>

</body>
</html>
<?php
