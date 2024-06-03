

<?php
require '../config.php';

$connect = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $connect = connexionDB();

    try {
        $stmt = $connect->prepare("DELETE FROM compte WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Ligne supprimée avec succès.";
        } else {
            echo "Erreur lors de la suppression de la ligne.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
