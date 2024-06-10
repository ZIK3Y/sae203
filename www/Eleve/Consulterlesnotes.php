<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style/eleve/Consulterlesnotes.css">
</head>
<body>

<header>
    <div class="EP">
        <div class="img">
            <a href="AcceuilEleve.php">
                <img src="../../ressources/image/Logo.png" alt="Logo de l'entreprise" class="logo">
            </a>
        </div>
        <div id="liste">
            <ul>
                <li><a href="Consulterlesnotes.php" class="bouton-23">Notes</a></li>
                <li><a href="Votrecompte.php" class="bouton-23">Compte</a></li>
                <li><a href="Parametre.php" class="bouton-23">Paramètres</a></li>
            </ul>
        </div>
        <div class="IC">
            <img src="../../ressources/image/personne.png" alt="Photo de profil" class="photo-profil">
        </div>
    </div>
</header>

<div class="C">
    <div class="PG">
        <div class="matiere" onclick="afficherDevoirs('devoirs1')">
            Matière 1
            <div class="devoirs" id="devoirs1">
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 1</div>
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 2</div>
            </div>
        </div>
        <div class="matiere" onclick="afficherDevoirs('devoirs2')">
            Matière 2
            <div class="devoirs" id="devoirs2">
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 1</div>
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 2</div>
            </div>
        </div>
        <div class="matiere" onclick="afficherDevoirs('devoirs3')">
            Matière 3
            <div class="devoirs" id="devoirs3">
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 1</div>
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 2</div>
            </div>
        </div>
        <div class="matiere" onclick="afficherDevoirs('devoirs4')">
            Matière 4
            <div class="devoirs" id="devoirs4">
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 1</div>
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 2</div>
            </div>
        </div>
        <div class="matiere" onclick="afficherDevoirs('devoirs5')">
            Matière 5
            <div class="devoirs" id="devoirs5">
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 1</div>
                <div class="devoir" onclick="mettreEnAvant(event)">Devoir 2</div>
            </div>
        </div>
    </div>
    <div class="PD">
        <div class="div11">
    <form>
  <canvas id="myChart" width="400" height="600"></canvas>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="binfo">
            <div>Nom de la note</div>
            <div>Votre Note : <span class="VN">20/20</span></div>
            <div>Moyenne de la classe : <span class="MC">12/20</span></div>
            <div class="dinfo">
                <div>
                    <h3>Coef :</h3>
                    <p>1.5</p>
                </div>
                <div>Matière</div>
            </div>
        </div>
    </div>
</div>
<script>
  const ctx = document.getElementById('myChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Hébergement - Note'],
      datasets: [
        {
          label: 'Votre note',
          data: [14],
          borderColor: '#36A2EB',
          backgroundColor: '#DDEAD7',
          barThickness: 60,
        },
        {
          label: 'Moyenne de la classe',
          data: [11.2],
          borderColor: '#FF6384',
          backgroundColor: '#FFB1C1',
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
<script>
function afficherDevoirs(idDevoirs) {
    var devoirs = document.getElementById(idDevoirs);
    if (devoirs.style.display === "block") {
        devoirs.style.display = "none";
    } else {
        devoirs.style.display = "block";
    }
}

function mettreEnAvant(event) {
    event.stopPropagation();
    var devoirs = document.querySelectorAll('.devoir');
    devoirs.forEach(function(devoir) {
        devoir.style.backgroundColor = '#506c4d';
    });
    event.target.style.backgroundColor = 'grey';
}
</script>
</body>
</html>