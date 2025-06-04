<?php
session_start();
if (isset($_SESSION['user'])) {
    // Redirect based on role if already logged in
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'mecanic') {
        header("Location: dashboard-mecanic.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if (isset($_POST['user']) && isset($_POST['pass'])) 
{
    $url = 'http://localhost/MaT_DoubleM/my-php-backend/public/index.php/login';

    $postData = [
        'user' => $_POST['user'],
        'pass' => $_POST['pass']
    ];

    curl_setopt($ch = curl_init($url), CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpcode === 200 && isset($data['token'])) {
        $payload = json_decode(base64_decode(explode('.', $data['token'])[1]), true);
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['jwt'] = $data['token'];
        $_SESSION['role'] = $payload['role'];
        if ($payload['role'] === 'admin') {
            header("Location:dashboard.php");
            exit();
        } elseif ($payload['role'] === 'mecanic') {
            header("Location:dashboard-mecanic.php");
            exit();
        } else {
            $error = 'Doar administratorii sau mecanicii pot accesa dashboard-ul!';
        }
    } else {
        $error = 'Utilizator inexistent sau parolă greșită.';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <title>Formular de login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="formular">
        <form method="POST" action="login.php">
            <label for="username">Nume utilizator:</label>
            <input type="text" name="user" id="username" placeholder="Nume utilizator" required>

            <label for="password">Parolă:</label>
            <input type="password" name="pass" id="password" placeholder="Parola" required>

            <input type="submit" value="Autentificare" class="submit">

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

        </form>
    </div>
</body>
</html>