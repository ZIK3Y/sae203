<?php
session_start();

require '../config.php';
$conn = connexionDB();

$id = $_SESSION['user'];
$prem = $_SESSION['perm'];

if(!isset($id) || $prem!=2) {
    header('Location : ../login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']==='POST') {
    $id_eval = $_POST['evaluation'];
    $libelle = $_POST['nom_eval'];
    $id_ressource = $_POST['ressource'];
    $coeff = $_POST['coeff'];

    $updateEval = $conn->prepare('UPDATE eval SET intitule = :libelle, id_ressource = :ressource, coeff = :coeff WHERE id_eval = :id_eval');
    $updateEval->bindParam(':libelle', $libelle);
    $updateEval->bindParam(':ressource', $id_ressource);
    $updateEval->bindParam(':id_eval', $id_eval);
    $updateEval->bindParam(':coeff', $coeff);
    $updateEval->execute();

    header('Location: notes.php');
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="starter-template">
        <h1>Modifier une évaluation :</h1>
        <form method="POST" action="modifier_note.php">
            <div class="form-group">
                <label for="evaluation" class="col-form-label">Evaluation :</label>
                    <select name="evaluation" id="evaluation">
                        <option value=''>--- Merci de choisir une évaluation ---</option>
                        <?php
                            $requeteEval = $conn->prepare("SELECT e.intitule, e.id_eval, promo.formation
                                                        FROM matiereens m
                                                        JOIN ressource r ON m.id_ressource = r.id_ressource
                                                        JOIN eval e ON r.id_ressource = e.id_ressource
                                                        JOIN ue ON r.ue = ue.id_ue
                                                        JOIN promotions promo ON ue.id_promo = promo.id_promo
                                                        WHERE m.id_ens = :id");
                            $requeteEval->bindParam(':id', $id);
                            $requeteEval->execute();

                            $evals = $requeteEval->fetchAll(PDO::FETCH_ASSOC);

                            if(isset($evals)) {
                                foreach($evals as $eval) {
                                    echo "<option value='" . $eval['id_eval'] . "'>" . $eval['intitule'] . " | Promotion : " . $eval['formation'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Aucune évaluation n'est disponible !</option>";
                            }
                        ?>
                    </select>
                </div>

            <div class="form-group">
                <label for="nom_eval" class="col-form-label">Libellé:</label>
                <input type="text" class="form-control" name="nom_eval" id="nom_eval" required>
            </div>


            <div class="form-group">
            <label for="ressource" class="col-form-label">Ressource :</label>
                <select name="ressource" id="ressource">
                <option value=''>--- Merci de choisir une ressource ---</option>
                <?php 
                        $requeteRessource = "SELECT r.id_ressource, r.intitule, promo.formation
                                            FROM enseignants ens 
                                            JOIN matiereens men ON ens.id_ens = men.id_ens 
                                            JOIN ressource r ON men.id_ressource = r.id_ressource 
                                            JOIN ue ue ON r.ue = ue.id_ue 
                                            JOIN promotions promo ON ue.id_promo = promo.id_promo 
                                            WHERE ens.id_ens = {$id};";
                        
                        $reponse = $conn->query($requeteRessource);

                    if (isset($reponse)) {
                        $rows = $reponse->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount = count($rows);

                        $reponse = $conn->query($requeteRessource);
                        if ($rowCount > 0) {
                            foreach ($rows as $row) {
                                $ressource = $row['id_ressource'];
                                echo "<option value='" . $row['id_ressource'] . "'>" . $row['intitule'] . " | Promotion : " . $row['formation'] . "</option>";
                            }
                        } else {
                            echo "<option value=''Aucune ressource n'est disponible !/option>";
                        }
                    } else {
                        echo "Une erreur s'est produite lors de l'exécution de la requête.";
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="coeff" class="col-form-label">Coefficient :</label>
                <input type="number" class="form-control" id="coeff" name="coeff">
                <br>
            </div>
            <div class="form-group">
                <a class="btn btn-secondary" href="notes.php" role="button">Annuler</a>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>