<?php
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'HR') {
    header("Location: login.php");
    exit;
}

$error = ""; $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $role = trim($_POST['role']);

    $stmt = $pdo->prepare("INSERT INTO employees (name, department, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $department, $role])) {
        $success = "Employee created successfully.";
    } else {
        $error = "Error creating employee.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Employee - StaffTrack</title>
  <link rel="stylesheet" href="styles.css">
  <script src="main.js" defer></script>
</head>
<body>
  <header><h1>Create Employee</h1></header>
  <main style="padding:20px;">
    <?php if($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>

    <form method="post" id="createForm">
      <label>Name</label><br>
      <input type="text" name="name" required><br><br>
      <label>Department</label><br>
      <select name="department" required>
        <option value="">Select</option>
        <option>Human Resources</option>
        <option>Finance</option>
        <option>Operations</option>
        <option>Sales</option>
      </select><br><br>
      <label>Role</label><br>
      <input type="text" name="role" required><br><br>
      <button type="submit" class="btn">Create</button>
    </form>
  </main>
  <script>validateForm("createForm");</script>
</body>
</html>
