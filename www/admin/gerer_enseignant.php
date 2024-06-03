
















<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="../../style/admin/gerer_enseignant.css">
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
        <a href="../../password.php" class="button-22" id="buttonenseignant">Ajouter Enseignant</a>
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

    // Début du tableau   
    echo "<table class='styled-table' border='1'>";     
    echo "<tr><th>Id</th><th>Nom</th><th>Prénom</th><th>Password</th><th>Niveau Permission</th><th>Actions</th></tr>";          

    // Parcours des données et affichage dans le tableau     
    foreach ($pdoreq as $ligne) {         
        echo "<tr id='row-" . $ligne['id'] . "'>";         
        echo "<td>" . $ligne['id'] . "</td>";         
        echo "<td>" . $ligne['nom'] . "</td>";         
        echo "<td>" . $ligne['prenom'] . "</td>";         
        echo "<td>" . $ligne['password'] . "</td>";         
        echo "<td>" . $ligne['niv_perm'] . "</td>";        
        echo "<td><div class='divbouton'><a href='#' class='boutonmodifier' type='submit'>Modifier</a><button class='boutonsupprimer' type='button' onclick='suppr(" . $ligne['id'] . ")'>Supprimer</button></div></td>";   
        echo "</tr>";     
    }     

    // Fin du tableau     
    echo "</table>"; 
    ?>
</body>
</html>

<script>
function suppr(id) {
    if (confirm("Voulez-vous supprimer la ligne?")) {
        // Supprimer la ligne visuellement
        var row = document.getElementById("row-" + id);
        row.parentNode.removeChild(row);

        // Supprimer de la base de données via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "supprimer.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                alert('Ligne supprimée avec succès.');
            }
        };
        xhr.send("id=" + id);
    }
}
</script>





