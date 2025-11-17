<?php
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== "HR") {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Employee ID required.");
}

$employee = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$employee->execute([$id]);
$employee = $employee->fetch();

if (!$employee) {
    die("Employee not found.");
}

$departments = $pdo->query("SELECT * FROM departments")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department_id = $_POST['department'];
    $role = $_POST['role'];
    $date_joined = $_POST['date_joined'];

    $stmt = $pdo->prepare("UPDATE employees SET name = ?, email = ?, phone = ?, department_id = ?, role = ?, date_joined = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $department_id, $role, $date_joined, $id]);
    header("Location: employee_profile.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="../public/logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section>
      <h1>Edit Employee</h1>

      <form method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($employee['name']); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($employee['email']); ?>" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($employee['phone']); ?>">

        <label for="department">Department</label>
        <select id="department" name="department" required>
          <?php foreach ($departments as $d): ?>
            <option value="<?= $d['id']; ?>" <?= $employee['department_id'] == $d['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($d['name']); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="role">Role</label>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($employee['role']); ?>">

        <label for="date_joined">Date Joined</label>
        <input type="date" id="date_joined" name="date_joined" value="<?= htmlspecialchars($employee['date_joined']); ?>">

        <button type="submit">Update</button>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 StaffTrack. All rights reserved.</p>
  </footer>
</body>
</html>
