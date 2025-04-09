<?php include 'header.php'; ?>

<section class="company-list">
    <section class="search-section">
        <div class="search-container">
            <h1 class="search-title">Trouve ton entreprise</h1>
            <form method="GET" action="index.php" class="search-form">
                <input type="hidden" name="action" value="liste_entreprise">
                <input type="text" name="mot_cle" value="<?= htmlspecialchars($motCle ?? '') ?>" placeholder="Mot-clé" class="search-input" />
                <input type="text" name="location" value="<?= htmlspecialchars($location ?? '') ?>" placeholder="Ville ou Code postal" class="location-input" />
                
                <!-- Choix nb entreprises par page -->
                <select name="limit" onchange="this.form.submit()" class="limit-selector">
                    <?php foreach ([6, 9, 12] as $opt): ?>
                        <option value="<?= $opt ?>" <?= (isset($_GET['limit']) && $_GET['limit'] == $opt) ? 'selected' : '' ?>>Afficher <?= $opt ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="search-button">Rechercher</button>
            </form>
        </div>
    </section>
</section>

<!-- Bouton Ajouter une entreprise (admin + ERP) -->
<?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'pilote')): ?>
    <div class="add-company-button-container">
        <a href="index.php?action=ajouter_entreprise" class="add-company-button">Ajouter une entreprise</a>
    </div>
<?php endif; ?>

<section class="companies-grid">
    <?php if (!empty($entreprises)): ?>
        <?php foreach ($entreprises as $entreprise): ?>
            <article class="company-card">
                <div class="company-content">
                <img src="assets/logo_entreprise/<?= htmlspecialchars($entreprise['logo']) ?>" alt="<?= htmlspecialchars($entreprise['nom']) ?> Logo" class="company-logo">
                    <div class="company-info">
                        <h2 class="company-name"><?= htmlspecialchars($entreprise['nom']) ?></h2>
                        <p class="company-description"><?= htmlspecialchars($entreprise['description'] ?: 'Aucune description') ?></p>
                        <p class="company-location"><?= htmlspecialchars($entreprise['ville'] . ', ' . $entreprise['code_postal']) ?></p>
                        <a href="index.php?action=details_entreprise&id=<?= $entreprise['id'] ?>" class="company-details">Détails de l'entreprise</a>
                        </div>
                </div>
                <div class="company-divider"></div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune entreprise trouvée.</p>
    <?php endif; ?>
</section>

<nav class="pagination">
    <?php if ($page > 1): ?>
        <a class="pagination-prev" href="index.php?action=liste_entreprise&mot_cle=<?= urlencode($motCle) ?>&location=<?= urlencode($location) ?>&page=<?= $page - 1 ?>&limit=<?= $limit ?>">&lt;- Précédent</a>
    <?php endif; ?>

    <span class="pagination-current">
        Page <?= $page ?> / <?= $totalPages ?>
    </span>

    <?php if ($page < $totalPages): ?>
        <a class="pagination-next" href="index.php?action=liste_entreprise&mot_cle=<?= urlencode($motCle) ?>&location=<?= urlencode($location) ?>&page=<?= $page + 1 ?>&limit=<?= $limit ?>">Suivant -&gt;</a>
    <?php endif; ?>
</nav>


<?php include 'footer.php'; ?>
