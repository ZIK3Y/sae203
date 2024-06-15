<?php
session_start();

require '../config.php';
$bdd = connexionDB();

error_reporting(0);
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 1) {
    header('Location: ../../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNote - Étudiant</title>
    <link rel="stylesheet" href="../../style/eleve/AcceuilEleve.css">
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
</head>
<body>
<header>
    <div class="headermain">
        <div class="img0">
            <a href="./accueil.php"><img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo"></a>
        </div>
        <div class="img1">
            <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
        </div>
    </div>
    <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
</header>
<div class="Fond">
<div class="bC">
    <div class="div1 ARM"><a href="Rang.php" class="button">Voir le Rang</a></div>
    <div class="div1 ARM"><a href="notes.php" class="button">Consulter les notes</a></div>
    <div class="div1 ARM"><a href="UE.php" class="button">Voir les UE</a></div>
</div>
</div>
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
