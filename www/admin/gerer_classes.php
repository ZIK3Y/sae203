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
               <li> <a href="gerer_enseignant.php" class="button-23">Gérer les Enseignants</a></li>
               <li> <a href="gerer_etudiant.php" class="button-23">Gérer les Étudiants</a></li> 
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
        <a href="../../password.php" class="button-22" id="buttonenseignant">Ajouter Classe</a>
    </div>

   <h1>a définir cette page  </h1>
</body>
</html>

<!-- ici c'est le script js pour la deconnexion et sont css en dessous -->
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
            top: 140px; /* Ajustez selon la hauteur de votre header */
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

<!-- ici c'est le script js pour la deconnexion et sont css en dessous //>




