<?php
session_start();
session_destroy();
?>
<script>
  localStorage.removeItem('jwt');
  window.location.href = 'login.php';
</script>
<?php
exit();