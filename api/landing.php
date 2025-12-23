<?php
require_once __DIR__ . "/../includes/includeDB.inc.php";
require_login();

$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home – Driving Experience</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Supervised Driving Experience</h1>
<h2>Hello, <?php echo $username; ?>!</h2>

<?php if (!empty($_GET['success'])): ?>
    <p style="color: green; font-weight: bold;">
        Driving experience saved successfully!
    </p>
<?php endif; ?>

<p>Select an action:</p>

<ul>
    <li><a href="../public/driving_experience.html">Enter a Driving Experience</a></li>
    <li><a href="../public/dashboard.html">View Summary</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>

