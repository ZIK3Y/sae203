<?php
require '../config.php';

$connect = connexionDB();

if (isset($_GET['id'])) {
    $stmt = $connect->prepare('SELECT * FROM compte WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $enseignant = $stmt->fetch();
    if (!$enseignant) {
        die('Enseignant non trouvé.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];
    $niv_perm = $_POST['niv_perm'];

    // hashage du mdp ici
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  
    $stmt = $connect->prepare('UPDATE compte SET nom = ?, prenom = ?, password = ?, niv_perm = ? WHERE id = ?');
    $stmt->execute([$nom, $prenom, $hashed_password, $niv_perm, $id]);
    if ($niv_perm == 1) {
        header('Location: gerer_etudiant.php');
    } elseif ($niv_perm == 2 || $niv_perm == 3) {
        header('Location: gerer_enseignant.php');
    } else {
        header('Location: gerer_enseignant.php');
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un Enseignant</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="starter-template">
        <h1>Modifier un Enseignant</h1>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($enseignant['id']) ?>">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($enseignant['nom']) ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($enseignant['prenom']) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?= htmlspecialchars($enseignant['password']) ?>" required>
            </div>
            <div class="form-group">
                <label for="niv_perm">Niveau Permission</label>
                <input type="number" class="form-control" id="niv_perm" name="niv_perm" value="<?= htmlspecialchars($enseignant['niv_perm']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="gerer_enseignant.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
