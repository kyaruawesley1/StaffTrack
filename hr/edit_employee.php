<?php
require_once __DIR__ . "/../config.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== "HR") {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if(!$id) { die("Employee ID required."); }

$employee = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$employee->execute([$id]);
$employee = $employee->fetch();

if(!$employee) die("Employee not found.");

$departments = $pdo->query("SELECT * FROM departments")->fetchAll();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=$_POST['name']; $email=$_POST['email']; $phone=$_POST['phone'];
    $department_id=$_POST['department']; $role=$_POST['role']; $date_joined=$_POST['date_joined'];

    $stmt=$pdo->prepare("UPDATE employees SET name=?, email=?, phone=?, department_id=?, role=?, date_joined=? WHERE id=?");
    $stmt->execute([$name,$email,$phone,$department_id,$role,$date_joined,$id]);
    header("Location: employee_profile.php?id=".$id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <h2 class="text-2xl font-bold mb-4">Edit Employee</h2>
  <form method="post" class="bg-white p-6 rounded shadow max-w-lg">
    <label>Name</label>
    <input type="text" name="name" value="<?= $employee['name'] ?>" class="w-full border p-2 mb-3 rounded">

    <label>Email</label>
    <input type="email" name="email" value="<?= $employee['email'] ?>" class="w-full border p-2 mb-3 rounded">

    <label>Phone</label>
    <input type="text" name="phone" value="<?= $employee['phone'] ?>" class="w-full border p-2 mb-3 rounded">

    <label>Department</label>
    <select name="department" class="w-full border p-2 mb-3 rounded">
      <?php foreach($departments as $d): ?>
        <option value="<?= $d['id'] ?>" <?= $employee['department_id']==$d['id'] ? 'selected':'' ?>><?= $d['name'] ?></option>
      <?php endforeach; ?>
    </select>

    <label>Role</label>
    <input type="text" name="role" value="<?= $employee['role'] ?>" class="w-full border p-2 mb-3 rounded">

    <label>Date Joined</label>
    <input type="date" name="date_joined" value="<?= $employee['date_joined'] ?>" class="w-full border p-2 mb-3 rounded">

    <button type="submit" class="btn bg-green-500 text-white px-4 py-2 rounded">Update</button>
  </form>
</body>
</html>
