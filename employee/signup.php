<?php
session_start();
require_once __DIR__ . "/../config.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = trim($_POST['employee_id']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if employee exists in employees table
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        $error = "Employee ID does not exist. Contact HR.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if account already exists
        $stmt2 = $pdo->prepare("SELECT * FROM wafanyakazi WHERE employee_id = ?");
        $stmt2->execute([$employee_id]);
        if ($stmt2->fetch()) {
            $error = "Account already exists for this Employee ID.";
        } else {
            // Insert account
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt3 = $pdo->prepare("INSERT INTO wafanyakazi (employee_id, password) VALUES (?, ?)");
            if ($stmt3->execute([$employee_id, $hashed_password])) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Failed to create account. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Signup - StaffTrack</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center font-sans">

<div class="bg-white p-8 rounded-xl shadow-lg w-96">
<h1 class="text-2xl font-bold text-red-600 text-center mb-6">Employee Signup</h1>

<?php if($error): ?>
<p class="bg-red-50 text-red-600 p-2 mb-4 rounded text-center"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if($success): ?>
<p class="bg-green-50 text-green-600 p-2 mb-4 rounded text-center"><?= htmlspecialchars($success) ?></p>
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
    <div>
        <label class="block text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="confirm_password" required class="w-full p-3 border rounded-lg">
    </div>
    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600">Sign Up</button>
</form>

<p class="text-center mt-4 text-sm text-gray-600">
Already have an account? <a href="login.php" class="text-red-600 hover:underline">Login here</a>
</p>
</div>
</body>
</html>
