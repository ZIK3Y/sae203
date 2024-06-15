<?php
session_start();

require '../config.php';
$conn = connexionDB();

$id = $_SESSION['user'];
$prem = $_SESSION['perm'];

if(!isset($id) || $prem!=2) {
    header('Location : ../../index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']==='POST') {
    $id_eval = $_POST['evaluation'];

    $deleteEval = $conn->prepare('DELETE FROM eval WHERE id_eval = :id_eval');
    $deleteEval->bindParam(':id_eval', $id_eval);
    $deleteEval->execute();

    header('Location: notes.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNote - Supprimer une évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
</head>
<body>
<div class="container">
    <div class="starter-template">
        <h1>Supprimer une évaluation :</h1>
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
                <a class="btn btn-secondary" href="notes.php" role="button">Annuler</a>
                <button type="submit" class="btn btn-primary">Supprimer</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>