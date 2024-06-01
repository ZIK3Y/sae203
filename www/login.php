<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/login.css">
    <title>Connexion</title>
</head>
<body>
    <div class="logoc">
        <img class="logoconnexion" src="../ressources/image/logoconnexion.png" alt="logoconnexion">
    </div>
    <div class="h1cc">
        <h1 class="h1c">Bienvenue sur UniNote, veuillez vous connectez</h1>
    </div>
    <div class="formulairec">
        <form action="form.php" method="POST">
            <label class="label1" for="login">Username </label><br>
            <input type="text" id="login" name="login" placeholder="Username" id="username"><br>
            <label class="label2" for="password">Password </label><br>
            <input type="password" id="password" name="password" placeholder="Password" id="password"> <br><br>
            <div class="validerinput">
                <input type="submit" value="Connexion">
            </div>
        </form>
    </div>
    <hr>
    <div class="logog">
        <img class="imageg" src="../ressources/image/logogustav.png" alt="logogustaveiffel">
    </div>
</body>
</html>

<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connexionDB();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM compte WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id'];

        switch ($user['niv_perm']) {
            case 1:
                header('Location: /eleves/menu.php');
            case 2:
                header('Location: /admin/menu.php');
            case 3:
                header('Location: /enseignant/menu.php');
        }
        header('Location: dashbord.php');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}

?>