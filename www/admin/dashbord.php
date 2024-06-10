<?php
session_start();
require '../config.php';

error_reporting(0);

$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 3) {
    header('Location: ../login.php');
    exit();
}

$bdd = connexionDB();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="../../style/admin/menuadmin.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNote</title>
   
<body>
    <header>
        <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
        <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
        <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
    </header>
    
    <div class="button-container">
        <div class="div1">  <a href="gerer_enseignant.php" class="button">Modifier les  Enseignants</a>
    </div>
      
        <div class="div2">
        <a href="gerer_classes.php" class="button">Modifier les<br> Classes</a>
        </div>
        <div class="div1">
        <a href="gerer_etudiant.php" class="button">Modifier les <br>
        Elèves</a>
        </div>
        
    </div>
    <hr>
</body>
</html>

<!-- ici c'est le script js pour la deconnexion et sont css en dessous -->
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
            top: 140px; /* Ajustez selon la hauteur de votre header */
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logout-bar a {
            text-decoration: none;
            color: #000;
        }
    </style>

<!-- ici c'est le script js pour la deconnexion et sont css en dessous //>


