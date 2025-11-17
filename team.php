<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/config.php";
try {
    // Fetch HR users only
    $stmt = $pdo->prepare("SELECT username FROM users WHERE role = 'HR'");
    $stmt->execute();
    $hr_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HR Team - StaffTrack</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<section id="team" class="team section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span class="description-title">HR Team</span>
    <h2>HR Team</h2>
    <p>Meet the Human Resource personnel responsible for managing staff and organizational planning.</p>
  </div>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row g-4">

      <?php if (count($hr_users) > 0): ?>
        <?php foreach ($hr_users as $index => $user): ?>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo 200 + ($index * 100); ?>">
            <div class="team-member">

              <!-- Removed image section but kept structure -->
              <div class="member-image bg-gray-200 h-48 w-full flex items-center justify-center rounded">
                <span class="text-gray-600 text-lg">No Image</span>
              </div>

              <div class="member-info">
                <h4><?php echo htmlspecialchars($user['username'] ?? 'Unknown User'); ?></h4>
                <span>HR</span>
                <p>No biography information available.</p>
              </div>

            </div>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <p class="text-center text-gray-600">No HR staff found in the database.</p>
      <?php endif; ?>

    </div>
  </div>

</section>

</body>
</html>
