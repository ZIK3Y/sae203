<?php
require '../config.php';

$connect = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        // ici ça supprime les info de la table ens`
        $stmt1 = $connect->prepare("DELETE FROM enseignants WHERE id_ens = :id");
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        // supprim les info de la table compte
        $stmt2 = $connect->prepare("DELETE FROM compte WHERE id = :id");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        echo "Informations enseignant et compte supprimées avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
