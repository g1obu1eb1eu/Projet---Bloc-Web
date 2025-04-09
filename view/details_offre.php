<?php

include 'header.php';

require_once 'model/OffreModel.php';
$offreModel = new OffreModel();

$id = $_GET['id'] ?? null; 

if (!$id) {
    echo "ID d'offre manquant.";
    exit;
}

// Récupérer l'offre à afficher
$offre = $offreModel->getOffreById($id);

// Vérifier si l'offre existe
if (!$offre) {
    echo "L'offre n'existe pas.";
    exit;
}

// Vérifier si l'utilisateur est l'auteur de l'offre (via entreprise_id) ou admin
$isAuthorOrAdmin = $_SESSION['role'] == 'admin';
?>

<!-- Affichage de l'offre -->
<h1>Détails de l'offre</h1>

<div>
    <h2><?php echo htmlspecialchars($offre['titre']); ?></h2>
    
    <!-- Affichage du logo -->
    <?php if (!empty($offre['logo'])): ?>
        <img src="assets/logo_entreprise/<?= htmlspecialchars($offre['logo']) ?>" alt="<?= htmlspecialchars($offre['entreprise']) ?> Logo" class="company-logo">
    <?php else: ?>
        <p>Aucun logo disponible.</p>
    <?php endif; ?>

    <p><strong>Entreprise :</strong> <?php echo htmlspecialchars($offre['entreprise']); ?></p>
    <p><strong>Localisation :</strong> <?php echo htmlspecialchars($offre['localisation']); ?></p>
    <p><strong>Compétences :</strong> <?php echo htmlspecialchars($offre['competence']); ?></p>
    <p><strong>Rémunération :</strong> <?php echo htmlspecialchars($offre['remuneration']); ?> €</p>
    <p><strong>Durée de l'offre :</strong> <?php echo htmlspecialchars($offre['duree_offre']); ?> jours</p>
    <p><strong>Date de publication :</strong> <?php echo htmlspecialchars($offre['date_publication']); ?></p>

    <!-- Affichage de la description -->
    <?php if (!empty($offre['description'])): ?>
        <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($offre['description'])); ?></p>
    <?php else: ?>
        <p>Aucune description disponible.</p>
    <?php endif; ?>
</div>

<?php if ($isAuthorOrAdmin): ?>
    <h3>Modifier l'offre</h3>

    <form action="index.php?action=modifier_offre&id=<?php echo $id; ?>" method="post">
        <div>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="<?php echo htmlspecialchars($offre['titre']); ?>" required>
        </div>

        <div>
            <label for="entreprise">Entreprise</label>
            <input type="text" name="entreprise" id="entreprise" value="<?php echo htmlspecialchars($offre['entreprise']); ?>" required>
        </div>

        <div>
            <label for="localisation">Localisation</label>
            <input type="text" name="localisation" id="localisation" value="<?php echo htmlspecialchars($offre['localisation']); ?>" required>
        </div>

        <div>
            <label for="competences">Compétences</label>
            <input type="text" name="competences" id="competences" value="<?php echo htmlspecialchars($offre['competence']); ?>" required>
        </div>

        <div>
            <label for="remuneration">Rémunération</label>
            <input type="number" name="remuneration" id="remuneration" value="<?php echo htmlspecialchars($offre['remuneration']); ?>" required>
        </div>

        <div>
            <label for="duree_offre">Durée de l'offre (en jours)</label>
            <input type="number" name="duree_offre" id="duree_offre" value="<?php echo htmlspecialchars($offre['duree_offre']); ?>" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($offre['description']); ?></textarea>
        </div>

        <div>
            <label for="date_publication">Date de publication</label>
            <input type="date" name="date_publication" id="date_publication" value="<?php echo htmlspecialchars($offre['date_publication']); ?>" required disabled>
        </div>

        <button type="submit" name="submit" value="modifier">Enregistrer les modifications</button>
    </form>
<?php endif; ?>


<!-- Bouton de suppression -->
<?php if ($isAuthorOrAdmin): ?>
    <form action="index.php?action=supprimer_offre&id=<?php echo $id; ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
        <button type="submit" name="submit" value="supprimer">Supprimer l'offre</button>
    </form>
<?php endif; ?>

<?php include 'footer.php'?>
