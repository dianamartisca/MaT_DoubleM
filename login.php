<?php
session_start();
if(isset($_SESSION['user']))
{
    header("Location: dashboard.php");
    exit();
}
else if(isset($_POST['pass']))
{

    $pdo=new PDO("mysql:host=localhost;dbname=issuesdb","root","");
    $stmt=$pdo->prepare("SELECT password FROM users WHERE user_name = ?");
    $stmt->execute([$_POST["user"]]);
    $data=$stmt->fetch(PDO::FETCH_ASSOC);

    if($data)
    {
        $pass=$data["password"];
        if($_POST['pass']===$pass)
        {
            $_SESSION['user']=$_POST['user'];
            header("Location:dashboard.php");
            exit();
        }
    }
    echo("Utilizatior inexistent sau parola gresita");
}
?> 



<!DOCTYPE html>
<html lang="ro">
<head>
    <title>Formular de login</title>
    <link rel="stylesheet" href="css/login.css"`>

    
</head>
<body>
    


    <div class="formular">
        <form method="POST">
            <label for="username">Nume admin:</label>
            <input type="text" name="user" id="username" placeholder="Nume admin" required>

            <label for="password">Parolă:</label>
            <input type="password" name="pass" id="password" placeholder="Parola" required>

            <input type="submit" value="Autentificare" class="submit">
        </form>
    </div>
</body>
</html>
