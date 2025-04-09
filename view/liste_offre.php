<?php
include 'header.php';

// Récupération des valeurs du formulaire
$entreprise = isset($_GET['entreprise']) ? trim($_GET['entreprise']) : '';
$competence = isset($_GET['competence']) ? trim($_GET['competence']) : '';
$localisation = isset($_GET['localisation']) ? trim($_GET['localisation']) : '';
$titre = isset($_GET['titre']) ? trim($_GET['titre']) : '';
$remuneration = isset($_GET['remuneration']) ? trim($_GET['remuneration']) : '';
$duree_offre = isset($_GET['duree_offre']) ? trim($_GET['duree_offre']) : '';
$date_offre = isset($_GET['date_offre']) ? trim($_GET['date_offre']) : '';

?>

<main class="company-list">
    <h1 class="search-title">Trouve ton offre</h1>

    <form class="login-form" action="index.php?action=liste_offre" method="GET">
        <input type="hidden" name="action" value="liste_offre">

        <div class="form-group">
          <label for="entreprise" class="form-label">Entreprise : </label>
          <input type="text" id="entreprise" name="entreprise" class="form-input" value="<?= htmlspecialchars($entreprise) ?>">
        </div>

        <div class="form-group">
          <label for="competence" class="form-label">Compétence :</label>
          <input type="text" id="competence" name="competence" class="form-input" value="<?= htmlspecialchars($competence) ?>">
        </div>

        <div class="form-group">
          <label for="localisation" class="form-label">Localisation :</label>
          <input type="text" id="localisation" name="localisation" class="form-input" value="<?= htmlspecialchars($localisation) ?>">
        </div>

        <div class="form-group">
          <label for="titre" class="form-label">Titre :</label>
          <input type="text" id="titre" name="titre" class="form-input" value="<?= htmlspecialchars($titre) ?>">
        </div>

        <div class="form-group">
          <label for="remuneration" class="form-label">Base de rémunération (€/mois) :</label>
          <input type="number" id="remuneration" name="remuneration" class="form-input" value="<?= htmlspecialchars($remuneration) ?>">
        </div>

        <div class="form-group">
          <label for="duree_offre" class="form-label">Durée de l'offre (semaines) :</label>
          <input type="number" id="duree_offre" name="duree_offre" class="form-input" value="<?= htmlspecialchars($duree_offre) ?>">
        </div>

        <div class="form-group">
          <label for="date_offre" class="form-label">Apparition de l'offre :</label>
          <select id="date_offre" name="date_offre" class="form-input">
            <option value="">Sélectionnez une période</option>
            <option value="24h" <?= $date_offre == '24h' ? 'selected' : '' ?>>Moins de 24 heures</option>
            <option value="3j" <?= $date_offre == '3j' ? 'selected' : '' ?>>Moins de 3 jours</option>
            <option value="7j" <?= $date_offre == '7j' ? 'selected' : '' ?>>Moins de 7 jours</option>
            <option value="2s" <?= $date_offre == '2s' ? 'selected' : '' ?>>Moins de 2 semaines</option>
            <option value="1m" <?= $date_offre == '1m' ? 'selected' : '' ?>>Moins de 1 mois</option>
            <option value="2m" <?= $date_offre == '2m' ? 'selected' : '' ?>>Moins de 2 mois</option>
            <option value="3m" <?= $date_offre == '3m' ? 'selected' : '' ?>>Moins de 3 mois</option>
            <option value="pi" <?= $date_offre == 'pi' ? 'selected' : '' ?>>Peu importe</option>
          </select>
        </div>

        <!-- Choix du nombre d'entreprises par page -->
        <select name="limit" onchange="this.form.submit()" class="limit-selector">
            <?php foreach ([6, 9, 12] as $opt): ?>
                <option value="<?= $opt ?>" <?= (isset($_GET['limit']) && $_GET['limit'] == $opt) ? 'selected' : '' ?>>Afficher <?= $opt ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="search-button">Rechercher</button>
        <a href="index.php?action=ajouter_offre" class="add-offer-button">Ajouter une offre</a>
    </form>

    <section class="companies-grid">
        <?php if (count($offres) > 0): ?>
            <?php foreach ($offres as $offre): ?>
                <article class="company-card">
                    <div class="company-content">
                        <img src="images/<?= htmlspecialchars($offre['logo']) ?>" alt="<?= htmlspecialchars($offre['titre']) ?> Logo" class="company-logo">
                        <div class="company-info">
                            <h2 class="company-name"><?= htmlspecialchars($offre['titre']) ?></h2>
                            <p class="company-description"><?= htmlspecialchars($offre['description'] ?: 'Aucune description') ?></p>
                            <p class="company-location"><?= htmlspecialchars($offre['localisation']) ?></p>
                            <a href="index.php?action=details_offre&id=<?= $offre['id'] ?>"class="company-details">Détails de l'offre</a>
                        </div>
                    </div>
                    <div class="company-divider"></div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune offre trouvée.</p>
        <?php endif; ?>
    </section>
    <nav class="pagination">
    <?php if ($page > 1): ?>
        <a class="pagination-prev"
           href="index.php?action=liste_offre
               &entreprise=<?= urlencode($entreprise) ?>
               &competence=<?= urlencode($competence) ?>
               &localisation=<?= urlencode($localisation) ?>
               &titre=<?= urlencode($titre) ?>
               &remuneration=<?= urlencode($remuneration) ?>
               &duree_offre=<?= urlencode($duree_offre) ?>
               &date_offre=<?= urlencode($date_offre) ?>
               &limit=<?= $limit ?>&page=<?= $page - 1 ?>">&lt;- Précédent</a>
    <?php endif; ?>

    <span class="pagination-current">
        Page <?= $page ?> / <?= $totalPages ?>
    </span>

    <?php if ($page < $totalPages): ?>
        <a class="pagination-next"
           href="index.php?action=liste_offre
               &entreprise=<?= urlencode($entreprise) ?>
               &competence=<?= urlencode($competence) ?>
               &localisation=<?= urlencode($localisation) ?>
               &titre=<?= urlencode($titre) ?>
               &remuneration=<?= urlencode($remuneration) ?>
               &duree_offre=<?= urlencode($duree_offre) ?>
               &date_offre=<?= urlencode($date_offre) ?>
               &limit=<?= $limit ?>&page=<?= $page + 1 ?>">Suivant -&gt;</a>
    <?php endif; ?>
</nav>
</main>


<?php include 'footer.php'; ?>
