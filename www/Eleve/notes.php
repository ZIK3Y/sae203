<?php
session_start();

require '../config.php';
error_reporting(0);

$conn = connexionDB();
$perm = $_SESSION['perm'];

if (!isset($_SESSION['user']) || $perm != 1) {
    header('Location: ../login.php');
    exit();
}

$id = $_SESSION['user'];

$requeteRessource = "SELECT r.id_ressource, r.intitule, promo.formation
                    FROM etudiant etud
                    JOIN promotions promo ON etud.promo = promo.id_promo 
                    JOIN ue ON promo.id_promo = ue.id_promo
                    JOIN ressource r ON ue.id_ue = r.ue
                    WHERE etud.id_etud = {$_SESSION['user']};";

$reponse = $conn->query($requeteRessource);
$resultatRessource = $reponse->fetchAll(PDO::FETCH_ASSOC);

$reponseCount = count($resultatRessource);

if($_SERVER['REQUEST_METHOD']==='GET') {
    $idEval = $_GET['id_eval'];

    if (isset($idEval)) {
        $requeteMoyenne = $conn->prepare('SELECT e.id_eval, p.id_promo, AVG(n.note) AS moyenne_notes 
                                          FROM notes n 
                                          JOIN etudiant et ON n.id_etud = et.id_etud 
                                          JOIN eval e ON n.id_eval = e.id_eval 
                                          JOIN ressource r ON e.id_ressource = r.id_ressource 
                                          JOIN ue u ON r.ue = u.id_ue 
                                          JOIN promotions p ON u.id_promo = p.id_promo 
                                          WHERE e.id_eval = :id_eval 
                                          GROUP BY e.id_eval, p.id_promo;');
        $requeteMoyenne->bindParam(':id_eval', $idEval);
        $requeteMoyenne->execute();

        $resultatMoyenne = $requeteMoyenne->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir ses notes</title>
    <link href="../../style/eleve/Consulterlesnotes.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

<header>
    <div class="EP">
        <div class="img0">
            <a href="AcceuilEleve.php">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </a>
        </div>
        <div id="liste">
            <ul>
                <li><a href="Rang.php" class="bouton-23">Voir le Rang</a></li>
                <li><a href="UE.php" class="bouton-23">Voir les UE</a></li>
            </ul>
        </div>
        <div class="IC">
            <img src="../../ressources/image/personne.png" alt="Photo de profil" class="photo-profil">
        </div>
    </div>
    <div class="logout-bar" id="logout-bar">
            <a href="../logout.php">Déconnexion</a>
        </div>
</header>

<div class="Placementencadrement">
<div class="BG">
<!-- Ressources -->
    <div class="container mt-4 s">
        <div class="accordion" id="ressourceAccordion">
            <?php
                if ($reponse) {
                    foreach ($resultatRessource as $ressource) {
                        $ressourceId = $ressource['id_ressource'];
                        echo '<div class="accordion-item Fonddéroulant">';
                        echo '<h2 class="accordion-header" id="heading' . $ressourceId . '">';
                        echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $ressourceId . '" aria-expanded="true" aria-controls="collapse' . $ressourceId . '">';
                        echo $ressource['intitule'] . " | Promotion : " . $ressource['formation'];
                        echo '</button>';
                        echo '</h2>';
                        echo '<div id="collapse' . $ressourceId . '" class="accordion-collapse collapse" aria-labelledby="heading' . $ressourceId . '" data-bs-parent="#ressourceAccordion">';
                        echo '<div class="accordion-body">';
                        echo '<ul class="list-group">';
    
                        $requeteEval = "SELECT * FROM eval WHERE id_ressource = :id_ressource";
                        $stmtEval = $conn->prepare($requeteEval);
                        $stmtEval->bindParam(':id_ressource', $ressourceId);
                        $stmtEval->execute();
                        $evals = $stmtEval->fetchAll(PDO::FETCH_ASSOC);
                        if ($evals) {
                            foreach ($evals as $eval) {
                                echo '<li class="list-group-item"><a href="?id_eval=' . $eval['id_eval'] . '">' . $eval['intitule'] . '</a> | Coefficient : ' . $eval['coeff'] . ' | Date : ' . $eval['date'] . '</li>';
                            }
                        } else {
                            echo '<li class="list-group-item">Aucune évaluation trouvée.</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>
<!-- Ressources -->
</div>

<div class="BD">

<!-- Tableau notes -->
<?php
  $requeteNote = $conn->prepare('SELECT e.coeff, e.intitule, e.id_eval, n.note, r.intitule AS matiere FROM eval e JOIN notes n ON e.id_eval = n.id_eval JOIN etudiant etu ON n.id_etud = etu.id_etud JOIN ressource r ON e.id_ressource = r.id_ressource WHERE e.id_eval = :id AND etu.id_etud = :etudiant');
  $requeteNote->bindParam(':id', $idEval);
  $requeteNote->bindParam(':etudiant', $id);
  $requeteNote->execute();

  $resultatNote = $requeteNote->fetchAll(PDO::FETCH_ASSOC);
    
  if(isset($resultatNote)) {
    foreach($resultatNote as $note) {

      }
  } else {
      echo "Il n'y a aucune note sur cette évaluation !" ;
  }
?>
<!-- Tableau notes -->



<!-- Charts JS-->
<div class="PD">
  <div class="div11">
    <form>
      <canvas id="myChart" width="400" height="600"></canvas>
    </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="binfo Bordure2">
          <h1> <strong><?php echo $note['intitule']; ?></strong> </h1>
          <strong> <div class="Taille">Votre Note : <span class="VN"><?php foreach($resultatNote as $note) { echo $note['note']; } ?></span></div></strong>
          <strong> <div class="Taille">Moyenne de la classe : <span class="MC"><?php echo isset($resultatMoyenne[0]['moyenne_notes']) ? $resultatMoyenne[0]['moyenne_notes'] : 0; ?></span></div></strong>
            <div class="dinfo">
                <div>
                    <h3>Coef :</h3>
                    <p class="Taille2"><?php foreach($resultatNote as $coeff) { echo $coeff['coeff']; } ?></p>
                </div>
                <div class="Taille3"><?php echo $note['matiere']; ?></div>
            </div>
        </div>
    </div>
</div>
<!-- Charts JS-->



</div>
</div>

</body>
</html>

<script>
  const canvas = document.getElementById('myChart');
  canvas.height = 450; // Ajustez cette valeur selon vos besoins
  canvas.width = 400; // Ajustez cette valeur selon vos besoins, si nécessaire

  const ctx = canvas.getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['<?php echo $note['intitule']; ?>'],
      datasets: [
        {
          label: 'Votre note',
          data: [<?php echo $note['note'] ?>],
          borderColor: '#36A2EB',
          backgroundColor: 'rgb(0, 102, 255)',
          barThickness: 60,
        },
        {
          label: 'Moyenne de la classe',
          data: [<?php echo isset($resultatMoyenne[0]['moyenne_notes']) ? $resultatMoyenne[0]['moyenne_notes'] : 0; ?>],
          borderColor: '#FF6384',
          backgroundColor: 'rgb(216, 14, 0)',
          barThickness: 30,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Comparaison des Notes'
        }
      },
      scales: {
        x: {
          stacked: false,
        },
        y: {
          min: 0,
          max: 20,
          beginAtZero: true
        }
      },
      layout: {
        padding: {
          left: 20,
          right: 20,
          top: 20,
          bottom: 20
        }
      },
      barPercentage: 0.5,
      categoryPercentage: 0.5
    }
  });
</script>


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

<!-- ici c'est le script js pour la deconnexion et sont css en dessous //>