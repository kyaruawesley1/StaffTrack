<?php
session_start();
require_once __DIR__ . "/../config.php";

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // Set session variables
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            // Role-based redirects
            if ($user['role'] === 'HR') {
                header("Location: ../hr/dashboard.php");
                exit;
            } elseif ($user['role'] === 'Department Head') {
                header("Location: ../dept/dashboard.php");
                exit;
            } elseif ($user['role'] === 'Administrator') {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                // Unknown role, log out just in case
                session_destroy();
                $error = "Unknown user role. Contact system admin.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
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
<body class="bg-gradient-to-br from-green-100 via-white to-green-200 min-h-screen flex flex-col items-center justify-center font-sans">

<main>
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-96 border-t-4 border-red-500">
        <h1 class="text-3xl font-bold mb-6 text-center text-red-600">StaffTrack Login</h1>

        <?php if($error): ?>
        <p class="text-red-500 mb-4 text-center text-sm font-semibold bg-red-50 p-2 rounded"><?= htmlspecialchars($error) ?></p>
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
                Log In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="hr_signup.php" class="text-red-600 font-medium hover:underline">Sign Up (HR only)</a>
        </p>
    </div>
</main>

<footer class="mt-10 text-gray-500 text-xs text-center">
&copy; <?= date("Y") ?> MetroWorks Enterprises StaffTrack. All rights reserved.
</footer>

</body>
</html>
