<?php
session_start();
require '../config.php';

$bdd = connexionDB();

function getMoyenne($intitule, $id_etud) {
    try {

        $stmt = $bdd->prepare("SELECT n.note, e.coeff FROM notes n INNER JOIN eval e ON n.id_eval = e.id_eval INNER JOIN ressource r ON e.id_ressource = r.id_ressource WHERE n.id_etud = :id_etud AND r.intitule = :intitule");
        $stmt->bindParam(':id_etud', $id_etud, PDO::PARAM_INT);
        $stmt->bindParam(':intitule', $intitule, PDO::PARAM_STR);
        $stmt->execute();
        
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_notes = 0;
        $total_coeffs = 0;

        if (count($resultat) > 0) {
            foreach ($resultat as $row) {
                $note = $row["note"];
                $coeff = $row["coeff"];

                $total_notes += $note * $coeff;
                $total_coeffs += $coeff;
            }

            if ($total_coeffs > 0) {
                $moyenne_ponderee = $total_notes / $total_coeffs;
                $moyenne_arrondie = round($moyenne_ponderee, 2);

                $color_class = "Important black";
                if ($moyenne_arrondie < 10) {
                    $color_class = "Important red";
                } elseif ($moyenne_arrondie > 14) {
                    $color_class = "Important green";
                }

                return "<p class='$color_class'>$moyenne_arrondie</p>";
            } else {
                return "<p>Aucun coefficient trouvé, impossible de calculer la moyenne.</p>";
            }
        } else {
            return "<p>Aucune note trouvée pour l'intitulé $intitule.</p>";
        }
    } catch (PDOException $e) {
        return "<p>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style/eleve/UE.css">
</head>
<body>
<header>
        <div class="headermain">
            <div class="img0">
               <a href="./AcceuilEleve.php"> <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo"></a>
            </div>
     
            <div id="list">
                <ul>
               <li><a href="./Consulterlesnotes.php" class="button-23">Consulter les notes</a></li>
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
    <div class="div1">
        <?php echo getMoyenne("Comprendre", 1); ?> <br>
        <p class="A">Comprendre</p>
    </div>

    <div class="div1">
        <?php echo getMoyenne("Concevoir", 1); ?> <br>
        <p class="A">Concevoir</p>
    </div>
    <div class="div1">
        <?php echo getMoyenne("Exprimer", 1); ?> <br>
        <p class="A">Exprimer</p>
    </div>
</div>

<div class="button-container2">
    <div class="div2">
        <?php echo getMoyenne("Développer", 1); ?> <br>
        <p class="A">Développer</p>
    </div>
    <div class="div2">
        <?php echo getMoyenne("Entreprendre", 1); ?> <br>
        <p class="A">Entreprendre</p>
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
