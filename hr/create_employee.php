<?php

require_once __DIR__ . "/../config.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== "HR") {
    header("Location: login.php");
    exit;
}

$success = $error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department_id = $_POST['department'];
    $role = $_POST['role'];
    $date_joined = $_POST['date_joined'];

    try {
        $stmt = $pdo->prepare("INSERT INTO employees (user_id, name, email, phone, department_id, role, date_joined) VALUES (NULL,?,?,?,?,?,?)");
        $stmt->execute([$name,$email,$phone,$department_id,$role,$date_joined]);
        $success = "Employee created successfully!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$departments = $pdo->query("SELECT * FROM departments")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="sales.css">
</head>
<body class="bg-gray-100 p-6">
  <h2 class="text-2xl font-bold mb-4">Create Employee</h2>

  <?php if($success): ?><p class="alert text-green-500"><?= $success ?></p><?php endif; ?>
  <?php if($error): ?><p class="alert text-red-500"><?= $error ?></p><?php endif; ?>

  <form method="post" class="bg-white p-6 rounded shadow max-w-lg">
    <label>Name</label>
    <input type="text" name="name" required class="w-full border p-2 mb-3 rounded">

    <label>Email</label>
    <input type="email" name="email" required class="w-full border p-2 mb-3 rounded">

    <label>Phone</label>
    <input type="text" name="phone" class="w-full border p-2 mb-3 rounded">

    <label>Department</label>
    <select name="department" required class="w-full border p-2 mb-3 rounded">
      <?php foreach($departments as $d): ?>
        <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
      <?php endforeach; ?>
    </select>

    <label>Role</label>
    <input type="text" name="role" class="w-full border p-2 mb-3 rounded">

    <label>Date Joined</label>
    <input type="date" name="date_joined" class="w-full border p-2 mb-3 rounded">

    <button type="submit" class="btn bg-blue-500 text-white px-4 py-2 rounded">Create</button>
  </form>
</body>
</html>
