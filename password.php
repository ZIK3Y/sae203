<?php
require 'www/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connexionDB();
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $niv_perm = $_POST['perm'];
    $mdp = $_POST['mdp'];

    $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

    $passman = $pdo->prepare('INSERT INTO compte VALUES (:id, :nom, :prenom, :password, :niv_perm)');
    $passman->bindParam(':id', $id);
    $passman->bindParam(':nom', $nom);
    $passman->bindParam(':prenom', $prenom);
    $passman->bindParam(':password', $hashedPassword);
    $passman->bindParam(':niv_perm', $niv_perm);
    $passman->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/password.css">

    <title>Account Manager</title>
<body>
<header>
        <div class="headermain">
            <div class="img0">
                <img src="./ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </div>
     
            <div id="list">
                <ul>
               <li><a href="www/admin/dashbord.php" class="button-23">Admin Dashbord</a></li>
               <li> <a href="www/admin/gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
               <li> <a href="www/admingerer_classes.php" class="button-23">Gérer les classes</a></li> 
                </ul>
            </div>
        
            <div class="img1">
                <img src="./ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
            </div>
        </div>
    </header>
    <form action="password.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="id">ID :</label>
                </td>
                <td>
                    <input type="number" id="id" name="id" placeholder="Entez un id"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nom">Nom :</label>
                </td>
                <td>
                    <input type="text" id="nom" name="nom" placeholder="Entez un nom"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="prenom">Prénom :</label>
                </td>
                <td>
                    <input type="text" id="prenom" name="prenom" placeholder="Entez un prénom"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="perm">Permission :</label>
                </td>
                <td>
                    <input type="number" id="perm" name="perm" placeholder="1 = Etudiant, 2 = Prof, 3 = Admin"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="mdp">Mot de passe :</label>
                </td>
                <td>
                    <input type="password" id="mdp" name="mdp" placeholder="Entez un mot de passe"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" id="valider" value="Valider"/>
                </td>
                <td>
                    <input type="reset" id="reinitialiser" value="Reset"/>
                </td>
            </tr>
        </table>
</body>
</html>
