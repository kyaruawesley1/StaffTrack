<?php
require_once __DIR__ . "/../config.php";

$error = "";
$success = "";

// Allowed roles
$allowed_roles = ['HR', 'Department Head', 'Administrator'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = $_POST['role'] ?? 'HR'; // default HR

    if (!in_array($role, $allowed_roles)) {
        $error = "Invalid role selected.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $role]);
            $success = "Account created successfully! You can now log in.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username already exists.";
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
  <title>StaffTrack Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-100 via-white to-green-200 min-h-screen flex flex-col items-center justify-center font-sans">

<main>
  <section class="bg-white shadow-2xl rounded-2xl p-8 w-96 border-t-4 border-red-500">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create Account</h2>

    <?php if($error): ?>
      <p class="text-red-500 mb-4 text-center"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if($success): ?>
      <p class="text-green-600 mb-4 text-center"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" class="space-y-5">
      <div>
        <label class="block text-gray-700 mb-1">Username</label>
        <input type="text" name="username" required class="w-full p-3 border rounded-lg">
      </div>

      <div>
        <label class="block text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required class="w-full p-3 border rounded-lg">
      </div>

      <div>
        <label class="block text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="confirm_password" required class="w-full p-3 border rounded-lg">
      </div>

      <div>
        <label class="block text-gray-700 mb-1">Role</label>
        <select name="role" required class="w-full p-3 border rounded-lg">
          <?php foreach($allowed_roles as $r): ?>
            <option value="<?= $r ?>" <?= (isset($role) && $role==$r) ? 'selected' : '' ?>><?= $r ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600">Sign Up</button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Already have an account? <a href="login.php" class="text-red-600 font-medium hover:underline">Login here</a>
    </p>
  </section>
</main>

<footer class="mt-10 text-gray-500 text-xs text-center">
  &copy; <?= date("Y") ?> MetroWorks Enterprises StaffTrack
</footer>
</body>
</html>
