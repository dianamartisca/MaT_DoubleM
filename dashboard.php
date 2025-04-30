<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
    <h1>Bun venit, <?php echo $_SESSION['user']; ?>!</h1>
    <p>Aceasta este zona de administrator.</p>
    <a href="logout.php">Logout</a>
</body>
</html>