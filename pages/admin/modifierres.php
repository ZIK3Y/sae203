<?php
session_start();
require '../config.php';

$pdo = connexionDB();

error_reporting(0);
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 3) {
    header('Location: ../../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM ressource WHERE id_ressource = ?');
    $stmt->execute([$_GET['id']]);
    $ressource = $stmt->fetch();
    if (!$ressource) {
        die('Ressource non trouvée.');
    }

    // Récupérer l'id de l'enseignant associé à la ressource
    $stmt = $pdo->prepare('SELECT id_ens FROM matiereens WHERE id_ressource = ?');
    $stmt->execute([$_GET['id']]);
    $matiereens = $stmt->fetch();
    if ($matiereens) {
        $id_ens = $matiereens['id_ens'];
    } else {
        $id_ens = null; // Pas d'enseignant associé
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ressource = $_POST['id_ressource'];
    $intitule = $_POST['intitule'];
    $ue = $_POST['ue'];
    $id_ens = $_POST['id_ens'];

    // Vérification des valeurs reçues
    if (empty($id_ressource) || empty($intitule) || empty($ue) || empty($id_ens)) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Update query to modify the resource
        $stmt = $pdo->prepare('UPDATE ressource SET intitule = ?, ue = ? WHERE id_ressource = ?');
        $stmt->execute([$intitule, $ue, $id_ressource]);

        // Update or insert into matiereens table
        $stmt = $pdo->prepare('SELECT * FROM matiereens WHERE id_ressource = ?');
        $stmt->execute([$id_ressource]);
        $existingMatiereens = $stmt->fetch();

        if ($existingMatiereens) {
            $stmt = $pdo->prepare('UPDATE matiereens SET id_ens = ? WHERE id_ressource = ?');
            $stmt->execute([$id_ens, $id_ressource]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO matiereens (id_ressource, id_ens) VALUES (?, ?)');
            $stmt->execute([$id_ressource, $id_ens]);
        }

        if ($stmt->rowCount() > 0) {
            header('Location: gerer_ressource.php');
            exit();
        } else {
            header('Location: gerer_ressource.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
    <title>UniNote - Modifier une Ressource</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="starter-template">
        <h1>Modifier une Ressource</h1>
        <form method="post" action="">
            <input type="hidden" name="id_ressource" value="<?= htmlspecialchars($ressource['id_ressource']) ?>">
            <div class="form-group">
                <label for="intitule">Intitulé</label>
                <input type="text" class="form-control" id="intitule" name="intitule" value="<?= htmlspecialchars($ressource['intitule']) ?>" required>
            </div>
            <div class="form-group">
                <label for="ue">UE</label>
                <input type="text" class="form-control" id="ue" name="ue" value="<?= htmlspecialchars($ressource['ue']) ?>" required>
            </div>
            <div class="form-group">
                <label for="id_ens">ID Enseignant</label>
                <input type="number" class="form-control" id="id_ens" name="id_ens" value="<?= htmlspecialchars($id_ens) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="gerer_ressource.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
