<?php
require_once __DIR__ . "/../includes/includeDB.inc.php";
require_login();

$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TRAX - Home</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
  <?php if (!empty($_GET['success'])): ?>
    <p style="color: green; font-weight: bold;">
        Driving experience saved successfully!
    </p>
<?php endif; ?>
  <h1>Welcome to TRAX, <?php echo $username; ?>!</h1>
  <p>Select an action below:</p>

  <div class="nav-buttons">

    <a href="../public/driving_experience.html">
      <div class="nav-card">
        <h3>Add Driving Experience</h3>
        <p>Record a new supervised driving session</p>
      </div>
    </a>

    <a href="../public/dashboard.html">
      <div class="nav-card">
        <h3>Dashboard</h3>
        <p>View your driving summary and statistics</p>
      </div>
    </a>

    <a href="logout.php">
      <div class="nav-card">
        <h3>Logout</h3>
        <p>Sign out of your account</p>
      </div>
    </a>

  </div>
  <div class="landing-footer">
    <p>TRAX - Tracks your experience on any track!</p>
    <p>Drive safely</p>
  </div>

</div>


</body>
</html>

