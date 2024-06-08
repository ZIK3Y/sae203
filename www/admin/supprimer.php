
<?php
require '../config.php';

$connect = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $connect = connexionDB();

    try {
        $stmt = $connect->prepare("DELETE FROM enseignants WHERE id_ens = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Informations enseignant supprimées avec succès.";
        } else {
            echo "Erreur lors de la suppression des informations enseignant.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
