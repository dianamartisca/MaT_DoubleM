<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'mecanic') 
{
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard Mecanic</title>
</head>
<link rel="stylesheet" href="css/dashboard.css">
<?php if (isset($_SESSION['jwt'])): ?>
<script>
  localStorage.setItem('jwt', '<?php echo $_SESSION['jwt']; ?>');
</script>
<?php endif; ?>

<body>
  <h1>Bun venit, <?php echo $_SESSION['user']; ?>!</h1>

  <nav>
    <ul>
      <li><a href="cereri.html">Cereri programare</a></li>
      <li><a href="stocuri.html">Stocuri</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

  </nav>
  <img src="logo2.png">
</body>

</html>