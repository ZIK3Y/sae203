<?php
session_start();

require '../config.php';
$bdd = connexionDB();

$id = $_SESSION['user'];

$requeteNbrEtu = $bdd->prepare("SELECT COUNT(*) AS nbr
                        FROM etudiant
                        WHERE promo = (SELECT promo FROM etudiant WHERE id_etud = :idetu);");
$requeteNbrEtu->bindParam(':idetu', $id);
$requeteNbrEtu->execute();

$resultatNbrEtu = $requeteNbrEtu->fetchAll(PDO::FETCH_ASSOC);

$requeteMG = $bdd->prepare("SELECT etu.id_etud, etu.promo, compte.nom, compte.prenom, SUM(n.note * e.coeff) / SUM(e.coeff) AS moyenne
                            FROM etudiant etu
                            JOIN compte ON etu.id_etud = compte.id
                            JOIN notes n ON etu.id_etud = n.id_etud
                            JOIN eval e ON n.id_eval = e.id_eval
                            WHERE etu.promo = (SELECT promo FROM etudiant WHERE id_etud = :id_etud)
                            GROUP BY etu.id_etud, etu.promo, compte.nom, compte.prenom
                            ORDER BY moyenne DESC;");
$requeteMG->bindParam(':id_etud', $id);
$requeteMG->execute();
$resultatMG = $requeteMG->fetchAll(PDO::FETCH_ASSOC);

$pos = 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style/eleve/Rang.css">
</head>
<body>
<header>
    <div class="EP">
        <div class="img0">
            <a href="AcceuilEleve.php">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </a>
        </div>
        <div id="liste">
            <ul>
                <li><a href="notes.php" class="bouton-23">Consulter les Notes</a></li>
                <li><a href="UE.php" class="bouton-23">Voir les UE</a></li>
            </ul>
        </div>
        <div class="IC">
            <img src="../../ressources/image/personne.png" alt="Photo de profil" class="photo-profil">
        </div>
        <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
    </div>
    
</header>
<div class="C">
    <div class="SB">
        <div class="SA"><?php   
        foreach($resultatMG as $rang) {
            if($rang['id_etud'] == $id) {
                echo $pos;
                break;
            }
            $pos++;
        }
        ?><br><span class="SA-label">Votre rang par promotion</span></div>
    </div>
    <div class="AB">
        <div class="A A-personnelle"><?php
        foreach($resultatMG as $rang) {
            if($rang['id_etud'] == $id) {
                echo round($rang['moyenne'], 2);
                break;
            }
        }
        ?><br><span class="A-label">Votre moyenne</span></div>
        <div class="A A-promotion"><?php
        $sum = 0;
        foreach($resultatMG as $rang) {
            $sum += $rang['moyenne'];
        }
        $avg = $sum / count($resultatMG);
        echo round($avg, 2);
        ?><br><span class="A-label">Moyenne de la promotion</span></div>
    </div>
</div>

</body>
</html>






<!-- ici c'est le script js pour la deconnexion et sont css en dessous -->
<script>
    document.querySelector('.photo-profil').addEventListener('click', function() {
        var logoutBar = document.getElementById('logout-bar');
        logoutBar.style.display = (logoutBar.style.display === 'none' || logoutBar.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        var isClickInside = document.querySelector('.photo-profil').contains(event.target) || document.getElementById('logout-bar').contains(event.target);
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
