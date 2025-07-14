<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'mecanic') {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Dashboard Mecanic</title>
</head>
<link rel="stylesheet" href="../../my-frontend/css/dashboard.css">

<body>
  <h1>Bun venit, <?php echo $_SESSION['user']; ?>!</h1>

  <nav>
    <ul>
      <li><a href="../../my-frontend/html/mecanic-cereri.html">Cereri programare</a></li>
      <li><a href="../../my-frontend/html/stocuri.html">Stocuri</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

  </nav>
  <img src="../../my-frontend/images/logo2.png">
</body>

</html>