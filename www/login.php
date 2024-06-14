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
        <img class="logoconnexion" src="../ressources/image/logo.png" alt="logoconnexion">
    </div>
    <div class="h1cc">
        <h1 class="h1c">Bienvenue sur UniNote, veuillez vous connectez</h1>
    </div>
    <div class="formulairec">
        <form action="login.php" method="POST">
            <label class="label1" for="login">Identifiant :</label><br>
            <input type="text" name="idAccount" id="idAccount" placeholder="Identifiant"><br>
            <label class="label2" for="password">Mot de passe :</label><br>
            <input type="password" id="password" name="password" placeholder="Mot de passe"> <br><br>
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
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connexionDB();
    $username = $_POST['idAccount'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM compte WHERE id = :id');
    $stmt->bindParam(':id', $username);
    $stmt->execute();
    $user = $stmt->fetch();


    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id'];
        $_SESSION['perm'] = $user['niv_perm'];

        switch ($user['niv_perm']) {
            case 1:
                header('Location: Eleve/AcceuilEleve.php');
                break;
            case 2:
                header('Location: enseignant/AccueilEnseignant.php');
                break;
            case 3:
                header('Location: admin/dashbord.php');
                break;
            default:
                $error = "Niveau de permission inconnu";
        }

        exit;
    } else {
        $error = "Mot de passe incorrect";
    }

}

if (isset($error)) {
    echo "<p>$error</p>";
}
?>
