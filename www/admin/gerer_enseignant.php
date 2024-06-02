<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="./gerer_enseignant.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNote</title>
</head>
<body>
    <header>
        <div class="headermain">
            <div class="img0">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </div>
     
            <div id="list">
                <ul>
               <li><a href="dashbord.php" class="button-23">Admin Dashbord</a></li>
               <li> <a href="gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
               <li> <a href="gerer_classes.php" class="button-23">Gérer les classes</a></li> 
                </ul>
            </div>
        
            <div class="img1">
                <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
            </div>
        </div>
    </header>
    <div class="div46">
    <h1>Liste des Enseignants</h1>
    </div>
   <div class="div45">
   <button class="button-22" type="button" id="buttonenseingant">Ajouter Enseignant</button>
   </div>



<?php
require '../config.php';

$connect = connexionDB();

// Écriture de la requête     
$req = "SELECT id, nom, prenom, password, niv_perm FROM compte WHERE niv_perm = 2 OR niv_perm = 3";          

// Exécution de la requête     
$pdoreq = $connect->query($req);          

// Définition du mode de la récupération des données     
$pdoreq->setFetchMode(PDO::FETCH_ASSOC);    



//Début du tableau   
echo  "<table class='styled-table' border='1'>";     
echo "<tr><th>Id</th><th>Nom</th><th>prenom</th><th>Password</th><th>niv_perm</th >
</tr>";          

// Parcours des données et affichage dans le tableau     
foreach ($pdoreq as $ligne) {         
    echo "<tr>";         
    echo "<td>" . $ligne['id'] . "</td>";         
    echo "<td>" . $ligne['nom'] . "</td>";         
    echo "<td>" . $ligne['prenom'] . "</td>";         
    echo "<td>" . $ligne['password'] . "</td>";         
    echo "<td>" . $ligne['niv_perm'] . "</td>";        
   
    echo "</tr>";     
   
    
}     

// Fin du tableau     
echo "</table>"; 

?>

</body>
</html>
