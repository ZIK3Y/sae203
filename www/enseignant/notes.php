<?php
session_start();

require '../config.php';

$conn = connexionDB();
error_reporting(0);
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 2) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_eval = $_POST['id_eval'];
    
    $intitule = $_POST['nom_eval'];
    $id_ressource = $_POST['ressource'];
    $coeff = $_POST['coeff'];

    if(isset($intitule) && isset($id_ressource) && isset($coeff)) {
        $stmt = $conn->prepare("INSERT INTO eval (id_ressource, coeff, intitule, date) VALUES (:id_ressource, :coeff, :intitule, NOW())");
        $stmt->bindParam(':id_ressource', $id_ressource);
        $stmt->bindParam(':coeff', $coeff);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->execute();
    }
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'notes_') === 0) {
            $id_etudiant = str_replace('notes_', '', $key);
            $note = $value;

            $stmtCheck = $conn->prepare("SELECT * FROM notes WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
            $stmtCheck->bindParam(':id_eval', $id_eval);
            $stmtCheck->bindParam(':id_etudiant', $id_etudiant);
            $stmtCheck->execute();
            $noteExist = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($noteExist) {
                $stmtUpdate = $conn->prepare("UPDATE notes SET note = :note WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
                $stmtUpdate->bindParam(':note', $note);
                $stmtUpdate->bindParam(':id_eval', $id_eval);
                $stmtUpdate->bindParam(':id_etudiant', $id_etudiant);
                $stmtUpdate->execute();
            } else {
                $stmtInsert = $conn->prepare("INSERT INTO notes (id_eval, id_etud, note) VALUES (:id_eval, :id_etudiant, :note)");
                $stmtInsert->bindParam(':note', $note);
                $stmtInsert->bindParam(':id_eval', $id_eval);
                $stmtInsert->bindParam(':id_etudiant', $id_etudiant);
                $stmtInsert->execute();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignant - Créer une évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

    <a class="btn btn-primary" id="ajouterEval" href="ajouter_note.php" role="button">Ajouter une évaluation</a>
    <a class="btn btn-primary" id="modifierEval" href="modifier_note.php" role="button">Modifier une évaluation</a>
    <a class="btn btn-primary" id="supprimerEval" href="supprimer_note.php" role="button">Supprimer une évaluation</a>

    <div class="container mt-4">
        <div class="accordion" id="ressourceAccordion">
            <?php
                $requeteRessource = "SELECT r.id_ressource, r.intitule, promo.formation
                FROM enseignants ens 
                JOIN matiereens men ON ens.id_ens = men.id_ens 
                JOIN ressource r ON men.id_ressource = r.id_ressource 
                JOIN ue ue ON r.ue = ue.id_ue 
                JOIN promotions promo ON ue.id_promo = promo.id_promo 
                WHERE ens.id_ens = {$_SESSION['user']};";

                $reponse = $conn->query($requeteRessource);

                if (isset($reponse)) {
                    $rows = $reponse->fetchAll(PDO::FETCH_ASSOC);
                    $rowCount = count($rows);
                    foreach ($rows as $ressource) {
                        $ressourceId = $ressource['id_ressource'];
                        echo '<div class="accordion-item">';
                        echo '<h2 class="accordion-header" id="heading' . $ressourceId . '">';
                        echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $ressourceId . '" aria-expanded="true" aria-controls="collapse' . $ressourceId . '">';
                        echo $ressource['intitule'] . " | Promotion : " . $row['formation'];
                        echo '</button>';
                        echo '</h2>';
                        echo '<div id="collapse' . $ressourceId . '" class="accordion-collapse collapse" aria-labelledby="heading' . $ressourceId . '" data-bs-parent="#ressourceAccordion">';
                        echo '<div class="accordion-body">';
                        echo '<ul class="list-group">';
    
                        $requeteEval = "SELECT * FROM eval WHERE id_ressource = :id_ressource";
                        $stmtEval = $conn->prepare($requeteEval);
                        $stmtEval->bindParam(':id_ressource', $ressourceId);
                        $stmtEval->execute();
                        $evals = $stmtEval->fetchAll(PDO::FETCH_ASSOC);
                        
                        if ($evals) {
                            foreach ($evals as $eval) {
                                echo '<li class="list-group-item"><a href="notes.php?id_eval=' . $eval['id_eval'] . '">' . $eval['intitule'] . '</a> | Coefficient : ' . $eval['coeff'] . ' | Date : ' . $eval['date'] . '</li>';
                            }
                        } else {
                            echo '<li class="list-group-item">Aucune évaluation trouvée.</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>
    
    <div class="container mt-4">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === 'GET' && isset($_GET['id_eval'])) {
        $id_eval = $_GET['id_eval'];
        
        $requeteEleves = "SELECT compte.id AS id_etudiant, compte.prenom, compte.nom, notes.note 
                          FROM compte 
                          JOIN etudiant ON compte.id = etudiant.id_etud  
                          JOIN promotions ON etudiant.promo = promotions.id_promo 
                          JOIN ue ON promotions.id_promo = ue.id_promo 
                          JOIN ressource ON ue.id_ue = ressource.ue 
                          JOIN eval ON ressource.id_ressource = eval.id_ressource 
                          LEFT JOIN notes ON eval.id_eval = notes.id_eval AND compte.id = notes.id_etud
                          WHERE eval.id_eval = :id_eval";

        $stmtEleves = $conn->prepare($requeteEleves);
        $stmtEleves->bindParam(':id_eval', $id_eval);
        $stmtEleves->execute();
        $eleves = $stmtEleves->fetchAll(PDO::FETCH_ASSOC);
        
        if ($eleves) {
            echo '<form action="notes.php" method="POST">';
            echo '<input type="hidden" name="id_eval" value="' . $id_eval . '">';
            echo '<table class="table">';
            echo '<tr><th>Identité</th><th>Note</th></tr>';
            foreach ($eleves as $eleve) {
                $note = isset($eleve['note']) ? $eleve['note'] : '';
                echo '<tr>';
                echo '<td>' . $eleve['prenom'] . ' ' . $eleve['nom'] . '</td>';
                echo '<td><input type="number" class="form-control" name="notes_' . $eleve['id_etudiant'] . '" value="' . $note . '" step="0.01" min="0" max="20"></td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<button type="submit" class="btn btn-primary">Enregistrer les notes</button>';
            echo '</form>';
        } else {
            echo '<p>Aucun élève trouvé pour cette évaluation.</p>';
        }
    }
    ?>
</div>

</body>
</html>