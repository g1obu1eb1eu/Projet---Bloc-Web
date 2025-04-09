<?php
session_start(); // Démarrer la session pour récupérer les infos de l'utilisateur

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

// Récupération des valeurs du formulaire
$mot_cle = isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

// Construction de la requête SQL
$sql = "SELECT * FROM entreprises WHERE 1=1";
$params = [];

if (!empty($mot_cle)) {
    $sql .= " AND nom LIKE ?";
    $params[] = "%$mot_cle%";
}

if (!empty($location)) {
    $sql .= " AND (ville LIKE ? OR code_postal LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}

// Exécution de la requête SQL avec PDO
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liste des entreprises</title>
    <link rel="stylesheet" href="assets/listes_des_entreprisess.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
    />
</head>
<body>
<header class="page-header">
    <div class="header-content">
        <div class="logo-section">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/5bb133dc7d3575f85487650c2d223e9666ba3e37229da5185408758834095c17?placeholderIfAbsent=true&apiKey=38bf6b9655d948da986891ebef71112e"
              alt="Company Logo"
              class="logo-image"
            />
            <nav class="breadcrumb">
              <a href="page_d_accueil.php" class="signup-link">Page d'accueil</a> |
              <a href="listes_des_entreprises.php" class="listes-des-entreprises-button">Liste des entreprises</a> |
              <a href="listes_des_offres.php" class="consulter-les-offres-button">Consulter les offres</a> 
            </nav>
            <div class="login-section">
              <?php if (isset($_SESSION['user_id'])): ?>
                  <a href="profil.php" class="login-button signup-link">Mon Profil</a>
                  <a href="deconnexion.php" class="login-button signup-link" style="color: red;">Déconnexion</a>
              <?php else: ?>
                  <a href="page_de_connexion.html" class="login-button signup-link">Connexion</a>
              <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main class="company-list">
    <section class="search-section">
        <div class="search-container">
            <h1 class="search-title">Trouve ton entreprise</h1>
            <form method="GET" action="listes_des_entreprises.php">
                <input type="text" name="mot_cle" value="<?= htmlspecialchars($mot_cle) ?>" placeholder="Mot-clé" class="search-input" />
                <div class="location-search">
                    <label for="location" class="location-label">Où ?</label>
                    <input type="text" id="location" name="location" value="<?= htmlspecialchars($location) ?>" class="location-input" placeholder="Ville ou Code postal" />
                </div>
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </section>

    <!-- Ajout du bouton "Ajouter une entreprise" uniquement pour les rôles admin et pilote -->
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'pilote')): ?>
        <div class="add-company-button-container">
            <a href="ajouter_entreprise.html" class="add-company-button">Ajouter une entreprise</a>
        </div>
    <?php endif; ?>

    <section class="companies-grid">
        <?php if (count($entreprises) > 0): ?>
            <?php foreach ($entreprises as $entreprise): ?>
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
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune entreprise trouvée.</p>
        <?php endif; ?>
    </section>
    <nav class="pagination">
        <button class="pagination-prev">&lt;- Précedent</button>
        <span class="pagination-current">1/MAX</span>
        <button class="pagination-next">Suivant -&gt;</button>
    </nav>
</main>

<footer class="site-footer">
    <img src="images/logo.png" alt="Logo Footer" class="footer-logo" />
    <nav class="footer-text">
        <a> © 2025 </a>
        <a name="ud_txt" href="A_propos.html"> A propos</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Accesibilite.html"> Accesibilité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Conditions_generales_d'utilisation.html"> Condition générales d'utilisation</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_de_confidentialité.html"> Politique de confidencialité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_relative_aux_cookies.html"> Politique relative aux cookies</a>
    </nav>
</footer>
</body>
</html>
