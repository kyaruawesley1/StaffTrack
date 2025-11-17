<?php
session_start();
require_once __DIR__ . "/../config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = trim($_POST['employee_id']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM wafanyakazi WHERE employee_id = ?");
    $stmt->execute([$employee_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['employee_id'] = $user['employee_id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid Employee ID or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Login - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center font-sans">

<div class="bg-white p-8 rounded-xl shadow-lg w-96">
<h1 class="text-2xl font-bold text-red-600 text-center mb-6">Employee Login</h1>

<?php if($error): ?>
<p class="bg-red-50 text-red-600 p-2 mb-4 rounded text-center"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" class="space-y-4">
    <div>
        <label class="block text-gray-700 mb-1">Employee ID</label>
        <input type="number" name="employee_id" required class="w-full p-3 border rounded-lg">
    </div>
    <div>
        <label class="block text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required class="w-full p-3 border rounded-lg">
    </div>
    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600">Login</button>
</form>

<p class="text-center mt-4 text-sm text-gray-600">
Don't have an account? <a href="signup.php" class="text-red-600 hover:underline">Sign up here</a>
</p>
</div>
</body>
</html>
