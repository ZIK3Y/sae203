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

$id = $_SESSION['user'];

$requeteUE = $bdd->prepare('SELECT ue.id_ue, ue.intitule, AVG(n.note) AS moyenne
                            FROM etudiant etu
                            JOIN notes n ON etu.id_etud = n.id_etud
                            JOIN eval e ON n.id_eval = e.id_eval
                            JOIN ressource r ON e.id_ressource = r.id_ressource
                            JOIN ue ON r.ue = ue.id_ue
                            WHERE etu.id_etud = :idetu
                            GROUP BY ue.id_ue');

$requeteUE->bindParam(':idetu', $id);
$requeteUE->execute();

$resultatUE = $requeteUE->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNote - Unités d'enseignements</title>
    <link rel="stylesheet" href="../../style/eleve/UE.css">
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
</head>
<body>
<header>
        <div class="headermain">
            <div class="img0">
               <a href="./accueil.php"> <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo"></a>
            </div>
     
            <div id="list">
                <ul>
               <li><a href="./notes.php" class="button-23">Consulter les notes</a></li>
               <li> <a href="./Rang.php" class="button-23">Votre Rang</a></li> 
                </ul>
            </div>
        
            <div class="img1">
                <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
            </div>
            <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
        </div>
       
    </header>

<div class="button-container">
<?php
    if(isset($resultatUE)) {
        foreach ($resultatUE as $ue) {
            $moyenne = round($ue['moyenne'], 2);
            $class = '';
            if ($moyenne < 8) {
                $class = 'red';
            } elseif ($moyenne >= 8 && $moyenne < 10) {
                $class = 'yellow';
            } elseif ($moyenne >= 10) {
                $class = 'green';
            }
            echo '<div class="div1">';
            echo '<p class="moyenne ' . $class . '">'. $moyenne . '</p><br>';
            echo '<p class="A">' . $ue['intitule'] . '</p>';
            echo '</div>';
        }
    } 
    ?>
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
