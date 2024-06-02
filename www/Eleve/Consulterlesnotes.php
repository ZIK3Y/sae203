<?php

require '../config.php';

$bdd = connexionDB();

function getMoyenne($intitule, $id_etud) {

    if ($connexion->connect_error) {
        die("Échec de la connexion : " . $connexion->connect_error);
    }

    $sql = "
        SELECT n.note, e.coeff
        FROM notes n
        INNER JOIN eval e ON n.id_eval = e.id_eval
        INNER JOIN ressource r ON e.id_ressource = r.id_ressource
        WHERE n.id_etud = ? AND r.intitule = ?
    ";

    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("is", $id_etud, $intitule);
    $stmt->execute();
    $resultat = $stmt->get_result();

    $total_notes = 0;
    $total_coeffs = 0;

    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            $note = $row["note"];
            $coeff = $row["coeff"];

            $total_notes += $note * $coeff;
            $total_coeffs += $coeff;
        }

        if ($total_coeffs > 0) {
            $moyenne_ponderee = $total_notes / $total_coeffs;
            return "<p>Moyenne : " . round($moyenne_ponderee, 2) . "</p>";
        } else {
            return "<p>Aucun coefficient trouvé, impossible de calculer la moyenne.</p>";
        }
    } else {
        return "<p>Aucune note trouvée pour l'intitulé $intitule.</p>";
    }

    $connexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./Consulterlesnotes.css">
</head>
<body>
<header>
    <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
    <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
</header>

<div class="button-container">
    <div class="div1">
        <?php echo getMoyenne("Comprendre", 1); ?>
        <p class="A">Comprendre</p>
    </div>

    <div class="div1">
        <?php echo getMoyenne("Concevoir", 1); ?>
        <p class="A">Concevoir</p>
    </div>
    <div class="div1">
        <?php echo getMoyenne("Exprimer", 1); ?>
        <p class="A">Exprimer</p>
    </div>
</div>

<div class="button-container2">
    <div class="div2">
        <?php echo getMoyenne("Développer", 1); ?>
        <p class="A">Développer</p>
    </div>
    <div class="div2">
        <?php echo getMoyenne("Entreprendre", 1); ?>
        <p class="A">Entreprendre</p>
    </div>
</div>



</body>
</html>
