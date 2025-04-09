<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'utilisateurs';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: page_de_connexion.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Requête pour récupérer les entreprises de la wishlist de l'utilisateur
$sql = "SELECT entreprises.* FROM entreprises JOIN wishlist ON entreprises.id = wishlist.entreprise_id WHERE wishlist.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$wishlist_entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la suppression d'une entreprise de la wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_wishlist'])) {
    $entreprise_id = $_POST['entreprise_id'];
    $sql = "DELETE FROM wishlist WHERE entreprise_id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$entreprise_id, $user_id]);
    header("Location: wishlist.php");
    exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Votre Wishlist</title>
    <link rel="stylesheet" href="assets/wishlistt.css" />
</head>
<body>
<header class="page-header">
    <h1>Votre Wishlist</h1>
</header>

<main class="wishlist-details">
    <section class="wishlist-grid">
        <?php if (count($wishlist_entreprises) > 0): ?>
            <?php foreach ($wishlist_entreprises as $entreprise): ?>
                <article class="company-card">
                    <div class="company-content">
                        <img src="images/<?= htmlspecialchars($entreprise['logo']) ?>" alt="<?= htmlspecialchars($entreprise['nom']) ?> Logo" class="company-logo">
                        <div class="company-info">
                            <h2 class="company-name"><?= htmlspecialchars($entreprise['nom']) ?></h2>
                            <p class="company-description"><?= htmlspecialchars($entreprise['description'] ?: 'Aucune description') ?></p>
                            <p class="company-location"><?= htmlspecialchars($entreprise['ville'] . ', ' . $entreprise['code_postal']) ?></p>
                            <a href="details_entreprise.php?id=<?= $entreprise['id'] ?>" class="company-details">Détails de l'entreprise</a>
                        </div>
                    </div>
                    <div class="company-divider"></div>
                    <!-- Bouton pour supprimer de la wishlist -->
                    <form method="POST" action="wishlist.php" class="remove-form">
                        <input type="hidden" name="entreprise_id" value="<?= $entreprise['id'] ?>">
                        <button type="submit" name="remove_from_wishlist" class="remove-button">Supprimer de la wishlist</button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Votre wishlist est vide.</p>
        <?php endif; ?>
    </section>
</main>

<footer class="site-footer">
    <img src="images/logo.png" alt="Logo Footer" class="footer-logo" />
    <nav class="footer-text">
        <a> © 2025 </a>
        <a name="ud_txt" href="A_propos.html"> A propos</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Accesibilite.html"> Accesibilité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Conditions_generales_d'utilisation.html"> Condition générales d'utilisation</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_de_confidentialite.html"> Politique de confidentialité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_relative_aux_cookies.html"> Politique relative aux cookies</a>
    </nav>
</footer>
</body>
</html>
