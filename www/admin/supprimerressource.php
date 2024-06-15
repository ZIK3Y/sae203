<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '../config.php';

$connect = connexionDB();


$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$id) {
    die('Erreur : ID non défini.');
}

try {
   
    $connect->beginTransaction();

  
    $stmt_select_eval = $connect->prepare('SELECT id_eval FROM eval WHERE id_ressource = ?');
    $stmt_select_eval->execute([$id]);
    $evals = $stmt_select_eval->fetchAll(PDO::FETCH_ASSOC);


    foreach ($evals as $eval) {
        $stmt_delete_notes = $connect->prepare('DELETE FROM notes WHERE id_eval = ?');
        $stmt_delete_notes->execute([$eval['id_eval']]);
    }

  
    $stmt_delete_eval = $connect->prepare('DELETE FROM eval WHERE id_ressource = ?');
    $stmt_delete_eval->execute([$id]);
    if ($stmt_delete_eval->rowCount() > 0) {
        echo "Enregistrements liés dans eval supprimés.<br>";
    } else {
        echo "Aucun enregistrement lié dans eval trouvé à supprimer.<br>";
    }

   
    $stmt_delete_matiereens = $connect->prepare('DELETE FROM matiereens WHERE id_ressource = ?');
    $stmt_delete_matiereens->execute([$id]);
    if ($stmt_delete_matiereens->rowCount() > 0) {
        echo "Enregistrements liés dans matiereens supprimés.<br>";
    } else {
        echo "Aucun enregistrement lié dans matiereens trouvé à supprimer.<br>";
    }

   
    $stmt_delete_ressource = $connect->prepare('DELETE FROM ressource WHERE id_ressource = ?');
    $stmt_delete_ressource->execute([$id]);

    if ($stmt_delete_ressource->rowCount() > 0) {
        echo "succès";
      
        $connect->commit();
    } else {
        echo "Erreur : Aucune ligne supprimée.";
    
        $connect->rollBack();
    }
} catch (PDOException $e) {
  
    $connect->rollBack();
    echo "Erreur : " . $e->getMessage();
}
?>
