<?php
session_start();

require '../config.php';
$pdo = connexionDB();

error_reporting(0);
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 3) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $promo = $_POST['promo'];
    $mdp = $_POST['mdp'];

    $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);



    // Insertion dans la table `compte`
    $stmt = $pdo->prepare('INSERT INTO compte (id, nom, prenom, password, niv_perm) VALUES (:id, :nom, :prenom, :password, 1)');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Insertion dans la table `etudiant`
    $stmt = $pdo->prepare('INSERT INTO etudiant (id_etud, promo) VALUES (:id, :promo)');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':promo', $promo);
    $stmt->execute();

    header('Location: ./gerer_etudiant.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/admin/gerer_enseignant.css">
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
    <title>UniNote - Ajouter un Étudiant</title>
</head>
<body>
    <header>
        <div class="headermain">
            <div class="img0">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </div>
            <div id="list">
                <ul>
                    <li><a href="dashbord.php" class="button-23">Admin Dashboard</a></li>
                    <li><a href="gerer_enseignant.php" class="button-23">Gérer les Enseignants</a></li>
                    <li><a href="gerer_ressource.php" class="button-23">Gérer les ressources</a></li> 
                </ul>
            </div>
            <div class="img1">
                <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
            </div>
        </div>
        <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
    </header>
    
   
<div class="divform">
<form action="ajouter_etudiant.php" method="post">
        <table>
            <tr>
                <td><label for="id">ID :</label></td>
                <td><input type="number" id="id" name="id" required></td>
            </tr>
            <tr>
                <td><label for="nom">Nom :</label></td>
                <td><input type="text" id="nom" name="nom" required></td>
            </tr>
            <tr>
                <td><label for="prenom">Prénom :</label></td>
                <td><input type="text" id="prenom" name="prenom" required></td>
            </tr>
            <tr>
                <td><label for="promo">Promotion :</label></td>
                <td><input type="text" id="promo" name="promo" required></td>
            </tr>
            <tr>
                <td><label for="mdp">Mot de passe :</label></td>
                <td><input type="password" id="mdp" name="mdp" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Ajouter"></td>
            </tr>
        </table>
    </form>
</div>
    

    <script>
        document.querySelector('.profile-pic').addEventListener('click', function() {
            var logoutBar = document.getElementById('logout-bar');
            logoutBar.style.display = (logoutBar.style.display === 'none' || logoutBar.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            var isClickInside = document.querySelector('.profile-pic').contains(event.target) || document.getElementById('logout-bar').contains(event.target);
            if (!isClickInside) {
                document.getElementById('logout-bar').style.display = 'none';
            }
        });
    </script>

    <style>
        .logout-bar {
            display: none;
            position: absolute;
            right: 10px;
            top: 140px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logout-bar a {
            text-decoration: none;
            color: #000;
        }
        form {
    margin: 20px auto;
    width: 400px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form table {
    width: 100%;
}

form table tr {
    margin-bottom: 15px; 
}

form table tr td:first-child {
    text-align: right;
    padding-right: 15px; 
    width: 35%;
}

form table tr td:last-child {
    width: 65%; 
}

form input[type="text"],
form input[type="number"],
form input[type="password"] {
    width: calc(100% - 10px); 
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

form input[type="submit"] {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 15px; 
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

form input[type="submit"]:focus {
    outline: none;
    
}
.divform{
margin-top:200px;

}

    </style>
</body>
</html>
<style>
     