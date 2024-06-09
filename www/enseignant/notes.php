<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration
require '../config.php';

// Établir la connexion à la base de données
$conn = connexionDB();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer les données du formulaire POST si soumises
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
    
    // Traiter les notes soumises
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'notes_') === 0) {
            $id_etudiant = str_replace('notes_', '', $key);
            $note = $value;

            // Vérifier si la note existe déjà
            $stmtCheck = $conn->prepare("SELECT * FROM notes WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
            $stmtCheck->bindParam(':id_eval', $id_eval);
            $stmtCheck->bindParam(':id_etudiant', $id_etudiant);
            $stmtCheck->execute();
            $noteExist = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($noteExist) {
                // Mettre à jour la note existante
                $stmtUpdate = $conn->prepare("UPDATE notes SET note = :note WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
                $stmtUpdate->bindParam(':note', $note);
                $stmtUpdate->bindParam(':id_eval', $id_eval);
                $stmtUpdate->bindParam(':id_etudiant', $id_etudiant);
                $stmtUpdate->execute();
            } else {
                // Insérer une nouvelle note
                $stmtInsert = $conn->prepare("INSERT INTO notes (id_eval, id_etud, note) VALUES (:id_eval, :id_etudiant, :note)");
                $stmtInsert->bindParam(':note', $note);
                $stmtInsert->bindParam(':id_eval', $id_eval);
                $stmtInsert->bindParam(':id_etudiant', $id_etudiant);
                $stmtInsert->execute();
            }
        }
    }
}

// Affichage du formulaire et des étudiants associés à l'évaluation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une évaluation :</title>
    <!-- Inclure les ressources Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Bouton pour ouvrir le modal d'ajout d'évaluation -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">Ajouter une évaluation</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">Supprimer une évaluation</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">Modifier une évaluation</button>

    <!-- Modal d'ajout d'évaluation -->
    <div class="modal fade" id="ajouterEval" tabindex="-1" aria-labelledby="ajouterEvalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <form action='notes.php' method='POST'>
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="ajouterEvalLabel">Ajouter une évaluation</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Formulaire d'ajout d'évaluation -->
            <form action="notes.php" method="POST">
            <div class="mb-3">
                <label for="nom_eval" class="col-form-label">Libellé:</label>
                <input type="text" class="form-control" name="nom_eval" id="nom_eval">
            </div>
            <div class="mb-3">
                <label for="ressource" class="col-form-label">Ressource :</label>
                <select name="ressource" id="ressource">
                <option value=''>--- Merci de choisir une ressource ---</option>
                <?php 
                    // Récupérer les ressources de l'utilisateur
                    $requeteRessource = "SELECT r.id_ressource, r.intitule, promo.formation
                                        FROM enseignants ens 
                                        JOIN matiereens men ON ens.id_ens = men.id_ens 
                                        JOIN ressource r ON men.id_ressource = r.id_ressource 
                                        JOIN ue ue ON r.ue = ue.id_ue 
                                        JOIN promotions promo ON ue.id_promo = promo.id_promo 
                                        WHERE ens.id_ens = {$_SESSION['user']};";

                    $reponse = $conn->query($requeteRessource);

                    // Afficher les options du select
                    if (isset($reponse)) {
                        $rows = $reponse->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount = count($rows);
                        if ($rowCount > 0) {
                            foreach ($rows as $row) {
                                echo "<option value='" . $row['id_ressource'] . "'>" . $row['intitule'] . " | Promotion : " . $row['formation'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>--- Merci de choisir une ressource ---</option>";
                        }
                    } else {
                        echo "Une erreur s'est produite lors de l'exécution de la requête.";
                    }
                ?>
                </select>
                <div class="mb-3">
                    <label for="coeff" class="col-form-label">Coefficient :</label>
                    <input type="number" class="form-control" id="coeff" name="coeff">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
        </div>
        </form>
    </div>
    </div>
    

        <!-- Affichage des ressources et évaluations associées -->
        <div class="container mt-4">
        <div class="accordion" id="ressourceAccordion">
            <?php
                // Afficher les ressources de l'utilisateur
                if ($reponse) {
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
    
                        // Récupérer les évaluations associées à la ressource
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
        
        // Récupérer les élèves associés à l'évaluation, même ceux sans note
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