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
<link rel="stylesheet" href="css/dashboard.css">

<body>
    <h1>Bun venit, <?php echo $_SESSION['user']; ?>!</h1>

    <nav>
  <ul>
    <li><a href="cereri.html">Cereri programare</a></li>
    <li><a href="stocuri.html">Stocuri</a></li>
    <li><a href="comenzi-furnizori.html">Comenzi furnizori</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

</body>
</html>