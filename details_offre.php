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

// Récupération de l'ID de l'offre depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupération de l'ID de l'utilisateur depuis la session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Requête pour récupérer les détails de l'offre
$sql = "SELECT * FROM offres WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    die("Offre non trouvée.");
}

// Requête pour récupérer les évaluations
$sql = "SELECT AVG(rating) as average_rating FROM evaluations WHERE offre_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$average_rating = $stmt->fetchColumn();

// Requête pour récupérer les commentaires
$sql = "SELECT * FROM commentaires WHERE offre_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour vérifier si l'offre est dans la wishlist de l'utilisateur
$sql = "SELECT * FROM wishlist WHERE offre_id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $user_id]);
$in_wishlist = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_rating'])) {
        $rating = $_POST['rating'];
        $sql = "INSERT INTO evaluations (offre_id, user_id, rating) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $user_id, $rating]);
        header("Location: details_offre.php?id=$id");
        exit();
    }

    if (isset($_POST['submit_comment'])) {
        $commentaire = $_POST['commentaire'];
        $sql = "INSERT INTO commentaires (offre_id, user_id, commentaire) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $user_id, $commentaire]);
        header("Location: details_offre.php?id=$id");
        exit();
    }

    if (isset($_POST['add_to_wishlist'])) {
        // Insérer l'offre dans la wishlist
        $sql = "INSERT INTO wishlist (offre_id, user_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$id, $user_id]);
            header("Location: details_offre.php?id=$id");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout à la wishlist : " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Détails de l'offre</title>
    <link rel="stylesheet" href="assets/details_offre.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Noto+Serif+Tamil:wght@500&display=swap"
      rel="stylesheet"
    />
</head>
<body>
<header class="page-header">
    <h1>Détails de l'offre</h1>
</header>

<main class="offer-details">
    <section class="offer-info">
        <div class="offer-header">
            <img src="images/<?= htmlspecialchars($offre['logo']) ?>" alt="<?= htmlspecialchars($offre['titre']) ?> Logo" class="offer-logo">
            <div class="offer-name-description">
                <h2 class="offer-name"><?= htmlspecialchars($offre['titre']) ?></h2>
                <p class="offer-description"><?= htmlspecialchars($offre['description']) ?></p>
            </div>
        </div>
        <div class="offer-stats">
            <p>Entreprise: <?= htmlspecialchars($offre['entreprise']) ?></p>
            <p>Localisation: <?= htmlspecialchars($offre['localisation']) ?></p>
            <p>Rémunération: <?= htmlspecialchars($offre['remuneration']) ?> €/mois</p>
            <p>Durée de l'offre: <?= htmlspecialchars($offre['duree_offre']) ?> semaines</p>
        </div>
    </section>

    <section class="reviews">
        <h3>Évaluations et commentaires</h3>
        <div class="average-rating">
            <p>Moyenne des évaluations: <?= $average_rating ?: 'Aucune évaluation' ?></p>
        </div>
        <form method="POST" action="details_offre.php?id=<?= $id ?>">
            <label for="rating">Donnez votre évaluation (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" required>
            <button type="submit" name="submit_rating">Soumettre l'évaluation</button>
        </form>
        <div class="comments">
            <h4>Commentaires</h4>
            <?php foreach ($commentaires as $commentaire): ?>
                <p><?= htmlspecialchars($commentaire['commentaire']) ?></p>
            <?php endforeach; ?>
            <form method="POST" action="details_offre.php?id=<?= $id ?>">
                <textarea name="commentaire" placeholder="Laissez un commentaire"></textarea>
                <button type="submit" name="submit_comment">Envoyer le commentaire</button>
            </form>
        </div>
    </section>

    <section class="wishlist">
        <?php if ($in_wishlist): ?>
            <p>Cette offre est dans votre wishlist.</p>
        <?php else: ?>
            <form method="POST" action="details_offre.php?id=<?= $id ?>">
                <button type="submit" name="add_to_wishlist">Ajouter à la wishlist</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
