<?php
session_start();
include 'pages/config.php';
error_reporting(0);

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connexionDB();
    $username = $_POST['idAccount'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM compte WHERE id = :id');
    $stmt->bindParam(':id', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id'];
        $_SESSION['perm'] = $user['niv_perm'];

        switch ($user['niv_perm']) {
            case 1:
                header('Location: pages/eleve/accueil.php');
                exit;
            case 2:
                header('Location: pages/enseignant/notes.php');
                exit;
            case 3:
                header('Location: pages/admin/dashbord.php');
                exit;
            default:
                $error = "Niveau de permission inconnu";
        }
    } else {
        $error = "Mot de passe incorrect";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
    <title>UniNote - Se connecter</title>
    <link rel="icon" type="image/png" href="ressources/image/logo.png">
</head>
<body>
    <div class="logoc">
        <img class="logoconnexion" src="ressources/image/logo.png" alt="logoconnexion">
    </div>
    <div class="h1cc">
        <h1 class="h1c">Bienvenue sur UniNote, veuillez vous connecter</h1>
    </div>
    <div class="formulairec">
        <form action="index.php" method="POST">
            <label class="label1" for="login">Identifiant :</label><br>
            <input type="text" name="idAccount" id="idAccount" placeholder="Identifiant"><br>
            <label class="label2" for="password">Mot de passe :</label><br>
            <input type="password" id="password" name="password" placeholder="Mot de passe"> <br>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="validerinput">
                <input type="submit" value="Connexion">
            </div>
           
        </form>
       
    </div>
    <hr>
    <div class="logog">
        <img class="imageg" src="ressources/image/logogustav.png" alt="logogustaveiffel">
    </div>



</body>
</html>