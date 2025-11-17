<?php
require_once __DIR__ . "/../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'] ?? $_SESSION['user']['id'];
$empStmt = $pdo->prepare("SELECT e.*, d.name as dept_name FROM employees e JOIN departments d ON e.department_id = d.id WHERE e.id = ?");
$empStmt->execute([$id]);
$employee = $empStmt->fetch();

if (!$employee) {
    die("Employee not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Profile</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
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
      <h1>Employee Profile</h1>

      <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']); ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($employee['email']); ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($employee['phone']); ?></p>
      <p><strong>Department:</strong> <?= htmlspecialchars($employee['dept_name']); ?></p>
      <p><strong>Role:</strong> <?= htmlspecialchars($employee['role']); ?></p>
      <p><strong>Date Joined:</strong> <?= htmlspecialchars($employee['date_joined']); ?></p>

      <?php if ($_SESSION['user']['role'] == "HR"): ?>
        <a href="edit_employee.php?id=<?= $employee['id']; ?>">Edit</a>
      <?php endif; ?>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 StaffTrack. All rights reserved.</p>
  </footer>
</body>
</html>
