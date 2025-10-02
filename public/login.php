<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../config.php";


$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            "id" => $user['id'],
            "username" => $user['username'],
            "role" => $user['role']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
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
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white shadow-lg rounded-lg p-8 w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    
    <?php if($error): ?>
      <p class="text-red-500 mb-4 text-sm"><?= htmlspecialchars($error) ?></p>
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
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Login</button>
    </form>
    <p class="mt-4 text-center text-sm">HR? <a href="hr_signup.php" class="text-blue-500 hover:underline">Create an account</a></p>
  </div>

</body>
</html>
