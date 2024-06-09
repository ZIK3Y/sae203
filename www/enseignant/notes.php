<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration
require '../config.php';

// Établir la connexion à la base de données
$conn = connexionDB();

// Rediriger si l'utilisateur n'est pas connecté
if(!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer les données du formulaire POST si soumises
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule = $_POST['nom_eval'];
    $id_ressource = $_POST['ressource'];
    $coeff = $_POST['coeff'];


    // Insérer les données dans la base de données si le formulaire est soumis
    if(isset($intitule) && isset($id_ressource) && isset($coeff)) {
        $stmt = $conn->prepare("INSERT INTO eval (id_ressource, coeff, intitule, date) VALUES (:id_ressource, :coeff, :intitule, NOW())");
        $stmt->bindParam(':id_ressource', $id_ressource);
        $stmt->bindParam(':coeff', $coeff);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->execute();
    }

}

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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">+</button>

    <!-- Modal d'ajout d'évaluation -->
    <div class="modal fade" id="ajouterEval" tabindex="-1" aria-labelledby="ajouterEvalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                    $requeteRessource = "SELECT ressource.id_ressource, ressource.intitule FROM enseignants JOIN matiereens ON enseignants.id_ens = matiereens.id_ens JOIN ressource ON matiereens.id_ressource = ressource.id_ressource WHERE enseignants.id_ens = {$_SESSION['user']};";
                    $reponse = $conn->query($requeteRessource);

                    // Afficher les options du select
                    if (isset($reponse)) {
                        $rows = $reponse->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount = count($rows);
                        if ($rowCount > 0) {
                            foreach ($rows as $row) {
                                echo "<option value='" . $row['id_ressource'] . "'>" . $row['intitule'] . "</option>";
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
    
    <!-- Formulaire pour choisir une promotion -->
    <div class="container mt-4">
        <form action="notes.php" method="post">
            <select class="form-select">
                <?php
                    // Récupérer les promotions de l'utilisateur
                    $requetePromotion = "SELECT p.id_promo, p.formation FROM enseignants ens JOIN matiereens me ON ens.id_ens = me.id_ens JOIN ressource r ON me.id_ressource = r.id_ressource JOIN ue ue ON r.ue = ue.id_ue JOIN promotions p ON ue.id_promo = p.id_promo WHERE ens.id_ens = {$_SESSION['user']};";
                    $reponsePromotion = $conn->query($requetePromotion);
                    
                    // Afficher les options du select
                    if(isset($reponsePromotion)) {
                        $rowsPromotion = $reponsePromotion->fetchAll(PDO::FETCH_ASSOC);
                        $countPromotion = count($rowsPromotion);
                        if($countPromotion > 0) {
                            foreach ($rowsPromotion as $promo) {
                                echo '<option value="' . $promo['id_promo'] . '">' . $promo['formation'] . '</option>';
                            }                         } else {
                                echo '<option value=""> -- Choisissez une promotion -- </option>';
                            }
                        } else {
                            echo "Une erreur s'est produite pendant l'execution de la requête !";
                        }
                    ?>
                </select>
            </form>
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
                        echo $ressource['intitule'];
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
            if($_SERVER["REQUEST_METHOD"]==='GET' && isset($eval['id_eval'])) {
                if(isset($eval['id_eval'])) {
                    echo '
                    <table class="table">
                        <tr>
                            <th>Identité</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </table>
                    ';
                }
            }
        ?>


    </div>

    </body>
    </html>
    
                       
