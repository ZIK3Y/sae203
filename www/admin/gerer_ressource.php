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
                    <li><a href="gerer_enseignant.php" class="button-23">Gérer les enseignants</a></li>
                    <li><a href="gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
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
        <h1>Liste des Ressources</h1>
    </div>
    <div class="div45">
        <a href="./ajouter_ressource.php" class="button-22" id="buttonressource">Ajouter Ressource</a>
    </div>
    
    <?php
    require '../config.php';

    $connect = connexionDB();

    $req = "SELECT res.id_ressource, res.intitule, res.ue, ens.id_ens, ens.mail 
            FROM ressource res 
            LEFT JOIN matiereens me ON res.id_ressource = me.id_ressource 
            LEFT JOIN enseignants ens ON me.id_ens = ens.id_ens";
    
    $pdoreq = $connect->query($req);

    $pdoreq->setFetchMode(PDO::FETCH_ASSOC);

    echo "<table class='styled-table' border='1'>";
    echo "<tr><th>Id Ressource</th><th>Intitulé</th><th>UE</th><th>Enseignant</th><th>Actions</th></tr>";

    foreach ($pdoreq as $ligne) {
        echo "<tr id='row-" . $ligne['id_ressource'] . "'>";
        echo "<td>" . $ligne['id_ressource'] . "</td>";
        echo "<td>" . $ligne['intitule'] . "</td>";
        echo "<td>" . $ligne['ue'] . "</td>";
        echo "<td>" . $ligne['id_ens'] . "</td>";
        echo "<td><div class='divbouton'><a href='modifierres.php?id=" . $ligne['id_ressource'] . "' class='boutonmodifier'>Modifier</a><button class='boutonsupprimer' type='button' onclick='suppr(" . $ligne['id_ressource'] . ")'>Supprimer</button></div></td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
</body>
</html>

<script>
function suppr(id) {
    if (confirm("Voulez-vous supprimer la ligne?")) {
        // Supprimer de la bdd via ajax
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "supprimerressource.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                if (xhr.responseText.includes("succès")) {
                    // Supprime la ligne visuellement après succès
                    var row = document.getElementById("row-" + id);
                    row.parentNode.removeChild(row);
                    alert('Ligne supprimée avec succès.');
                } else {
                    alert('Erreur lors de la suppression : ' + xhr.responseText);
                }
            }
        };
        xhr.send("id=" + id);
    }
}

</script>

<!-- Script JS pour la déconnexion -->
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
