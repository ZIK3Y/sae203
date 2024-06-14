<?php
session_start();    

require '../config.php';
$pdo = connexionDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $niv_perm = $_POST['perm'];
    $mdp = $_POST['mdp'];

    $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

    $passman = $pdo->prepare('INSERT INTO compte VALUES (:id, :nom, :prenom, :password, :niv_perm)');
    $passman->bindParam(':id', $id);
    $passman->bindParam(':nom', $nom);
    $passman->bindParam(':prenom', $prenom);
    $passman->bindParam(':password', $hashedPassword);
    $passman->bindParam(':niv_perm', $niv_perm);
    $passman->execute();

    if($niv_perm == 2) {
        $requeteEnseignant = "INSERT INTO enseignants VALUES ({$id}, 0000000000, 'adefinir@adressemail.com');";
        $reponseEnseignant = $pdo->query($requeteEnseignant);

    }
  
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/admin/gerer_enseignant.css">

    <title>Account Manager</title>
<body>
<header>
        <div class="headermain">
            <div class="img0">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </div>
     
            <div id="list">
                <ul>
               <li><a href="./dashbord.php" class="button-23">Admin Dashbord</a></li>
               <li> <a href="./gerer_etudiant.php" class="button-23">Gérer les étudiants</a></li>
               <li> <a href="./gerer_ressource.php" class="button-23">Gérer les ressources</a></li> 
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
    <form action="ajouter_enseignant.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="id">ID :</label>
                </td>
                <td>
                    <input type="number" id="id" name="id" placeholder="Entez un id"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nom">Nom :</label>
                </td>
                <td>
                    <input type="text" id="nom" name="nom" placeholder="Entez un nom"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="prenom">Prénom :</label>
                </td>
                <td>
                    <input type="text" id="prenom" name="prenom" placeholder="Entez un prénom"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="perm">Permission :</label>
                </td>
                <td>
                    <input type="number" id="perm" name="perm" placeholder="1 = Etudiant, 2 = Prof, 3 = Admin"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="mdp">Mot de passe :</label>
                </td>
                <td>
                    <input type="password" id="mdp" name="mdp" placeholder="Entez un mot de passe"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" id="valider" value="Valider"/>
                </td>
                <td>
                    <input type="reset" id="reinitialiser" value="Reset"/>
                </td>
            </tr>
        </table>
    </div>
   
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
.divformulaire{
margin-top:200px;

}

    </style>
</body>
</html>
