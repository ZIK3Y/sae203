<?php
session_start();
require '../config.php';

$connect = connexionDB();

error_reporting(0);
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 3) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        // Supprimer de la table `etudiant`
        $stmt1 = $connect->prepare("DELETE FROM etudiant WHERE id_etud = :id");
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        // Supprimer de la table `compte`
        $stmt2 = $connect->prepare("DELETE FROM compte WHERE id = :id");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        echo "Informations étudiant et compte supprimées avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
