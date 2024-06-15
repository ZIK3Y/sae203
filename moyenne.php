<?php
$bdd = connexionDB();

function calcul_moyenne($id_eval) {
    $requeteCalcul = $bdd->prepare("SELECT NOTES.note, EVAL.coeff 
            FROM NOTES 
            INNER JOIN EVAL ON NOTES.id_eval = EVAL.id_eval
            INNER JOIN ETUDIANT ON ETUDIANT.id_etudiant = NOTES.id_etudiant WHERE EVAL.id_eval = :id_eval");
    $requeteCalcul->bindParam(':id_eval', $id_eval);
    $requeteCalcul->execute();

    $numbers = [];
    $coef = [];

    if ($requeteCalcul->num_rows > 0) {
        while($row = $requeteCalcul->fetch_assoc()) {
            $numbers[] = $row['note'];
            $coef[] = $row['coeff'];
        }
    } else {
        echo "0 results";
        $conn->close();
        exit();
    }

    $somme = 0;
    $sommecoef = 0;
    for ($i = 0; $i < count($numbers); $i++) {
        $somme += $numbers[$i] * $coef[$i];
        $sommecoef += $coef[$i];
    }

    $average = $somme / $sommecoef;
    return($average);
}

?>