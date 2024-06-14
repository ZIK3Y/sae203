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
                    <li><a href="dashbord.php" class="button-23">Admin Dashboard</a></li>
                    <li><a href="gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
                    <li><a href="gerer_ressource.php" class="button-23">Gérer les ressources</a></li>
                </ul>
            </div>
            <div class="img1">
                <img src="../../ressources/image/personne.png" alt="Photo de profil" class="profile-pic">
            </div>
        </div>
        <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
    </header>
    <div class="div46">
        <h1>Liste des Enseignants</h1>
    </div>
    <div class="div45">
        <a href="./ajouter_enseignant.php" class="button-22" id="buttonenseignant">Ajouter Enseignant</a>
    </div>
    
    <?php
    require '../config.php';

    $connect = connexionDB();

    $req = "SELECT cpt.id, cpt.nom, cpt.prenom, cpt.password, cpt.niv_perm, ens.num_tel, ens.mail 
            FROM compte cpt 
            JOIN enseignants ens ON cpt.id = ens.id_ens 
            WHERE cpt.niv_perm = 2";          
    
    $pdoreq = $connect->query($req);          

    $pdoreq->setFetchMode(PDO::FETCH_ASSOC);    

    echo "<table class='styled-table' border='1'>";     
    echo "<tr><th>Id</th><th>Nom</th><th>Prénom</th><th>N° de Téléphone</th><th>Adresse mail</th><th>Password</th><th>Niveau Permission</th><th>Actions</th></tr>";          

    foreach ($pdoreq as $ligne) {         
        echo "<tr id='row-" . $ligne['id'] . "'>";         
        echo "<td>" . $ligne['id'] . "</td>";         
        echo "<td>" . $ligne['nom'] . "</td>";         
        echo "<td>" . $ligne['prenom'] . "</td>";
        echo "<td>" . $ligne['num_tel'] . "</td>";
        echo "<td>" . $ligne['mail'] . "</td>";       
        echo "<td>" . $ligne['password'] . "</td>";         
        echo "<td>" . $ligne['niv_perm'] . "</td>";        
        echo "<td><div class='divbouton'><a href='modifierens.php?id=" . $ligne['id'] . "' class='boutonmodifier'>Modifier</a><button class='boutonsupprimer' type='button' onclick='suppr(" . $ligne['id'] . ")'>Supprimer</button></div></td>";   
        echo "</tr>";     
    }     

    echo "</table>"; 
    ?>
</body>
</html>

<script>
function suppr(id) {
    if (confirm("Voulez-vous supprimer la ligne?")) {
        // Supprime la ligne visuellement mais la supprime pas sur la bdd
        var row = document.getElementById("row-" + id);
        row.parentNode.removeChild(row);

        // Supprimer la bdd via ajax
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

        // Supprimer également de la table "enseignants"
        var xhr2 = new XMLHttpRequest();
        xhr2.open("POST", "supprimer_enseignant.php", true);
        xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr2.onreadystatechange = function() {
            if (xhr2.readyState === 4 && xhr2.status === 200) {
                console.log(xhr2.responseText);
                //alert('Informations enseignant supprimées avec succès.');
            }
        };
        xhr2.send("id=" + id);
    }
}
</script>

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
</style>
