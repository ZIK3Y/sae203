<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration
require '../config.php';

// Établir la connexion à la base de données
$conn = connexionDB();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Initialiser $reponse pour éviter les erreurs de variable non définie
$reponse = [];

// Récupérer les ressources de l'utilisateur
$requeteRessource = "SELECT r.id_ressource, r.intitule, promo.formation
                     FROM enseignants ens 
                     JOIN matiereens men ON ens.id_ens = men.id_ens 
                     JOIN ressource r ON men.id_ressource = r.id_ressource 
                     JOIN ue ue ON r.ue = ue.id_ue 
                     JOIN promotions promo ON ue.id_promo = promo.id_promo 
                     WHERE ens.id_ens = :user_id";
$stmtRessource = $conn->prepare($requeteRessource);
$stmtRessource->bindParam(':user_id', $_SESSION['user']);
$stmtRessource->execute();
$reponse = $stmtRessource->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les données du formulaire POST si soumises
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_eval = $_POST['id_eval'] ?? null;
    $intitule = $_POST['nom_eval'] ?? null;
    $id_ressource = $_POST['ressource'] ?? null;
    $coeff = $_POST['coeff'] ?? null;

    if ($intitule && $id_ressource && $coeff) {
        $stmt = $conn->prepare("INSERT INTO eval (id_ressource, coeff, intitule, date) VALUES (:id_ressource, :coeff, :intitule, NOW())");
        $stmt->bindParam(':id_ressource', $id_ressource);
        $stmt->bindParam(':coeff', $coeff);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->execute();
    }

    // Traiter les notes soumises
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'notes_') === 0) {
            $id_etudiant = str_replace('notes_', '', $key);
            $note = $value;

            // Vérifier si la note existe déjà
            $stmtCheck = $conn->prepare("SELECT * FROM notes WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
            $stmtCheck->bindParam(':id_eval', $id_eval);
            $stmtCheck->bindParam(':id_etudiant', $id_etudiant);
            $stmtCheck->execute();
            $noteExist = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($noteExist) {
                // Mettre à jour la note existante
                $stmtUpdate = $conn->prepare("UPDATE notes SET note = :note WHERE id_eval = :id_eval AND id_etud = :id_etudiant");
                $stmtUpdate->bindParam(':note', $note);
                $stmtUpdate->bindParam(':id_eval', $id_eval);
                $stmtUpdate->bindParam(':id_etudiant', $id_etudiant);
                $stmtUpdate->execute();
            } else {
                // Insérer une nouvelle note
                $stmtInsert = $conn->prepare("INSERT INTO notes (id_eval, id_etud, note) VALUES (:id_eval, :id_etudiant, :note)");
                $stmtInsert->bindParam(':note', $note);
                $stmtInsert->bindParam(':id_eval', $id_eval);
                $stmtInsert->bindParam(':id_etudiant', $id_etudiant);
                $stmtInsert->execute();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une évaluation</title>
    <link href="../../style/enseignant/ModifierlesNotes.css" rel="stylesheet">
    <!-- Inclure les ressources Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
                <li><a href="Votrecompte.php" class="bouton-23">Compte</a></li>
                <li><a href="Parametre.php" class="bouton-23">Paramètres</a></li>
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

<div class="container mt-4">
    <div class="row">
        <!-- Box de gauche : matières et devoirs -->
        <div class="col-md-6">
            <!-- Affichage des ressources et évaluations associées -->
            <div class="accordion" id="ressourceAccordion">
                <?php if (!empty($reponse)): ?>
                    <?php foreach ($reponse as $ressource): ?>
                        <?php $ressourceId = htmlspecialchars($ressource['id_ressource']); ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $ressourceId; ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $ressourceId; ?>" aria-expanded="true" aria-controls="collapse<?php echo $ressourceId; ?>">
                                    <?php echo htmlspecialchars($ressource['intitule']) . " | Promotion : " . htmlspecialchars($ressource['formation']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $ressourceId; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $ressourceId; ?>" data-bs-parent="#ressourceAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <?php
                                        // Récupérer les évaluations associées à la ressource
                                        $requeteEval = "SELECT * FROM eval WHERE id_ressource = :id_ressource";
                                        $stmtEval = $conn->prepare($requeteEval);
                                        $stmtEval->bindParam(':id_ressource', $ressourceId);
                                        $stmtEval->execute();
                                        $evals = $stmtEval->fetchAll(PDO::FETCH_ASSOC);
                                        ?>
                                        <?php if (!empty($evals)): ?>
                                            <?php foreach ($evals as $eval): ?>
                                                <li class="list-group-item">
                                                    <a href="notes.php?id_eval=<?php echo htmlspecialchars($eval['id_eval']); ?>">
                                                        <?php echo htmlspecialchars($eval['intitule']); ?>
                                                    </a> | Coefficient : <?php echo htmlspecialchars($eval['coeff']); ?> | Date : <?php echo htmlspecialchars($eval['date']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item">Aucune évaluation trouvée.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune ressource trouvée.</p>
                <?php endif; ?>
            </div>
            <!-- Boutons sous la box de gauche -->
            <div class="mt-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEval" data-bs-whatever="@getbootstrap">Ajouter une évaluation</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supprimerEval" data-bs-whatever="@getbootstrap">Supprimer une évaluation</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifierEval" data-bs-whatever="@getbootstrap">Modifier une évaluation</button>
            </div>
        </div>

        <!-- Box de droite : identités et notes -->
        <div class="col-md-6">
            <div class="notes-container">
                <?php
                if ($_SERVER["REQUEST_METHOD"] === 'GET' && isset($_GET['id_eval'])) {
                    $id_eval = $_GET['id_eval'];
                    
                    // Récupérer les élèves associés à l'évaluation, même ceux sans note
                    $requeteEleves = "SELECT compte.id AS id_etudiant, compte.prenom, compte.nom, notes.note 
                                      FROM compte 
                                      JOIN etudiant ON compte.id = etudiant.id_etud  
                                      JOIN promotions ON etudiant.promo = promotions.id_promo 
                                      JOIN ue ON promotions.id_promo = ue.id_promo 
                                      JOIN ressource ON ue.id_ue = ressource.ue 
                                      JOIN eval ON ressource.id_ressource = eval.id_ressource 
                                      LEFT JOIN notes ON eval.id_eval = notes.id_eval AND compte.id = notes.id_etud
                                      WHERE eval.id_eval = :id_eval";

                    $stmtEleves = $conn->prepare($requeteEleves);
                    $stmtEleves->bindParam(':id_eval', $id_eval);
                    $stmtEleves->execute();
                    $eleves = $stmtEleves->fetchAll(PDO::FETCH_ASSOC);

                    if ($eleves) {
                        echo '<form action="notes.php" method="POST">';
                        echo '<input type="hidden" name="id_eval" value="' . htmlspecialchars($id_eval) . '">';
                        echo '<table class="table">';
                        echo '<tr><th>Identité</th><th>Note</th></tr>';
                        foreach ($eleves as $eleve) {
                            $note = isset($eleve['note']) ? htmlspecialchars($eleve['note']) : '';
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($eleve['prenom']) . ' ' . htmlspecialchars($eleve['nom']) . '</td>';
                            echo '<td><input type="number" class="form-control" name="notes_' . htmlspecialchars($eleve['id_etudiant']) . '" value="' . $note . '" step="0.01" min="0" max="20"></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '<button type="submit" class="btn btn-primary">Enregistrer les notes</button>';
                        echo '</form>';
                    } else {
                        echo '<p>Aucun élève trouvé pour cette évaluation.</p>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout d'évaluation -->
<div class="modal fade" id="ajouterEval" tabindex="-1" aria-labelledby="ajouterEvalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action='notes.php' method='POST'>
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ajouterEvalLabel">Ajouter une évaluation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire d'ajout d'évaluation -->
                    <div class="mb-3">
                        <label for="nom_eval" class="col-form-label">Libellé:</label>
                        <input type="text" class="form-control" name="nom_eval" id="nom_eval">
                    </div>
                    <div class="mb-3">
                        <label for="ressource" class="col-form-label">Ressource :</label>
                        <select name="ressource" id="ressource" class="form-select">
                            <option value=''>--- Merci de choisir une ressource ---</option>
                            <?php foreach ($reponse as $row): ?>
                                <option value="<?php echo htmlspecialchars($row['id_ressource']); ?>">
                                    <?php echo htmlspecialchars($row['intitule']) . " | Promotion : " . htmlspecialchars($row['formation']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="coeff" class="col-form-label">Coefficient :</label>
                        <input type="number" class="form-control" id="coeff" name="coeff">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>



<!-- ici c'est le script js pour la deconnexion et sont css en dessous -->
<script>
    document.querySelector('.photo-profil').addEventListener('click', function() {
        var logoutBar = document.getElementById('logout-bar');
        logoutBar.style.display = (logoutBar.style.display === 'none' || logoutBar.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        var isClickInside = document.querySelector('.photo-profil').contains(event.target) || document.getElementById('logout-bar').contains(event.target);
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
