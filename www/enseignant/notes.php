<?php
require '../config.php';

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// connect database
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'UniNote';

// connect
$conn = connexionDB();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
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

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une évaluation :</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">+</button>

    <div class="modal fade" id="ajouterEval" tabindex="-1" aria-labelledby="ajouterEvalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="ajouterEvalLabel">Ajouter une évaluation</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
                    $requeteRessource = "SELECT ressource.id_ressource, ressource.intitule FROM enseignants INNER JOIN matiereens ON enseignants.id_ens = matiereens.id_ens INNER JOIN ressource ON matiereens.id_ressource = ressource.id_ressource WHERE enseignants.id_ens = {$_SESSION['user']};";
                    $reponse = $conn->query($requeteRessource);

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

        <input type="button" value="Add">
        <input type="button" value="Suppr">
        <input type="button" value="Edit">
        
        <input type="submit" value="Valider">

</body>
</html>