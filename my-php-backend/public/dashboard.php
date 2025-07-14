<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard</title>
</head>
<link rel="stylesheet" href="../../my-frontend/css/dashboard.css">
<?php if (isset($_SESSION['jwt'])): ?>
  <script>
    localStorage.setItem('jwt', '<?php echo $_SESSION['jwt']; ?>');
  </script>
<?php endif; ?>

<body>
  <h1>Bun venit, <?php echo $_SESSION['user']; ?>!</h1>

  <nav>
    <ul>
      <li><a href="../../my-frontend/html/cereri.html">Cereri programare</a></li>
      <li><a href="../../my-frontend/html/stocuri.html">Stocuri</a></li>
      <li><a href="../../my-frontend/html/comenzi-furnizori.html">Comenzi furnizori</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

  </nav>
  <img src="../../my-frontend/images/logo2.png">
</body>

</html>