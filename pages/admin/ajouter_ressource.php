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
// Récupérer la liste des promotions depuis la base de données
$stmt_promotions = $pdo->query('SELECT * FROM promotions');
$promotions_list = $stmt_promotions->fetchAll(PDO::FETCH_ASSOC); // Renommage de la variable pour éviter le conflit

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule = $_POST['intitule'];
    $ue = $_POST['ue'];
    $selected_promotion = $_POST['promotion']; // Renommage de la variable pour éviter le conflit

    try {
        // Insérer la nouvelle ressource avec l'UE sélectionnée
        $stmt = $pdo->prepare('INSERT INTO ressource (intitule, ue) VALUES (:intitule, :ue)');
        $stmt->bindParam(':intitule', $intitule);
        $stmt->bindParam(':ue', $ue);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Ressource ajoutée avec succès.";
        } else {
            echo "Erreur lors de l'ajout de la ressource.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/admin/gerer_enseignant.css">
    <link rel="icon" type="image/png" href="../../ressources/image/logo.png">
    <title>UniNote - Ajouter une Ressource</title>
    <style>
        /* Styles spécifiques à la page */
        /* Tu peux ajouter tes styles CSS ici */
    </style>
</head>
<body>
<header>
    <div class="headermain">
        <div class="img0">
            <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
        </div>
        <div id="list">
            <ul>
                <li><a href="./dashbord.php" class="button-23">Admin Dashbord</a></li>
                <li><a href="./gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
                <li><a href="./gerer_ressource.php" class="button-23">Gérer les ressources</a></li>
            </ul>
        </div>
        <div class="img1">
            <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
        </div>
        <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
    </div>
</header>
<div class="divformulaire">
    <form action="ajouter_ressource.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="intitule">Intitulé :</label>
                </td>
                <td>
                    <input type="text" id="intitule" name="intitule" placeholder="Entrez un intitulé" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ue">UE :</label>
                </td>
                <td>
                    <input type="text" id="ue" name="ue" placeholder="Entrez une UE" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="promotion">Promotion :</label>
                </td>
                <td>
                    <select id="promotion" name="promotion" required>
                        <option value="">Sélectionnez une promotion</option>
                        <?php foreach ($promotions_list as $promotion) : ?>
                            <option value="<?php echo $promotion['id_promo']; ?>"><?php echo $promotion['formation']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" id="valider" value="Valider">
                    <input type="reset" id="reinitialiser" value="Reset">
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Script JavaScript pour la gestion du menu de déconnexion -->
<script>
    document.querySelector('.profile-pic').addEventListener('click', function() {
        var logoutBar = document.getElementById('logout-bar');
        logoutBar.style.display = (logoutBar.style.display === 'none' || logoutBar.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        var isClickInside = document.querySelector('.profile-pic').contains(event.target) || document.getElementById('logout-bar').contains(event.target);
        if (!isClickInside) {
            document.getElementById('logout-bar').style.display = 'none';
        }
    });
</script>

<!-- Styles CSS spécifiques pour la mise en forme du formulaire -->
<style>
    .logout-bar {
        display: none;
        position: absolute;
        right: 10px;
        top: 140px;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .logout-bar a {
        text-decoration: none;
        color: #000;
    }

    form {
        margin: 20px auto;
        width: 400px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form table {
        width: 100%;
    }

    form table tr {
        margin-bottom: 15px;
    }

    form table tr td:first-child {
        text-align: right;
        padding-right: 15px;
        width: 35%;
    }

    form table tr td:last-child {
        width: 65%;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="password"] {
        width: calc(100% - 10px);
        padding: 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    form select {
        width: calc(100% - 10px);
        padding: 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    form input[type="submit"] {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    form input[type="submit"]:focus {
        outline: none;
    }

    .divformulaire {
        margin-top: 50px;
    }
</style>

</body>
</html>
