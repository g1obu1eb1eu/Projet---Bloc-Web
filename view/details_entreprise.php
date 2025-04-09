<?php include 'header.php';
// var_dump($_SESSION);
?>

<main class="company-details-e">
    <h1 class="search-title">Détails de l'entreprise</h1>
    <section class="company-info-e">
        <div class="company-header-e">
            <img src="assets/logo_entreprise/<?= htmlspecialchars($entreprise['logo']) ?>" alt="<?= htmlspecialchars($entreprise['nom']) ?> Logo" class="company-logo">
            <div class="company-name-description-e">
                <h2 class="company-name-e"><?= htmlspecialchars($entreprise['nom']) ?></h2>
                <p class="company-description-e"><?= htmlspecialchars($entreprise['description']) ?></p>
            </div>
        </div>
        <div class="company-stats-e">
            <p>Nombre d'offres disponibles: <?= htmlspecialchars($entreprise['nombre_offres']) ?></p>
        </div>
    </section>

    <section class="reviews-e">
        <h3>Évaluations et commentaires</h3>
        <div class="average-rating-e">
        <p>Moyenne des évaluations: <?= $average_rating ? number_format($average_rating, 2) : 'Aucune évaluation' ?></p>
        </div>
        <form method="POST" action="index.php?action=details_entreprise&id=<?= $id ?>">
            <label for="rating">Donnez votre évaluation (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" required>
            <button type="submit" name="submit_rating">Soumettre l'évaluation</button>
        </form>
        <div class="comments-e">
            <h4>Commentaires</h4>
                <?php foreach ($commentaires as $commentaire): ?>
                    <p>
                        <!-- Affichage des initiales de l'utilisateur -->
                        <strong>
                            <?= strtoupper(substr($commentaire['prenom_utilisateur'], 0, 1)) ?>
                            <?= strtoupper(substr($commentaire['nom_utilisateur'], 0, 1)) ?>
                        </strong>
                        : <?= htmlspecialchars($commentaire['commentaire']) ?>
                    </p>
                <?php endforeach; ?>
                <form method="POST" action="index.php?action=details_entreprise&id=<?= $id ?>">
                    <textarea name="commentaire" placeholder="Laissez un commentaire"></textarea>
                    <button type="submit" name="submit_comment">Envoyer le commentaire</button>
                </form>
            </div>
    </section>

    <section class="wishlist-e">
        <?php if ($in_wishlist): ?>
            <p>Cette entreprise est dans votre wishlist.</p>
        <?php else: ?>
            <form method="POST" action="index.php?action=details_entreprise&id=<?= $id ?>">
                <button class='logout-button' type="submit" name="add_to_wishlist">Ajouter à la wishlist</button>
            </form>
        <?php endif; ?>
    </section>
</main>

<?php include 'footer.php'; ?>