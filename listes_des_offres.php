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
$entreprise = isset($_GET['entreprise']) ? trim($_GET['entreprise']) : '';
$competence = isset($_GET['competence']) ? trim($_GET['competence']) : '';
$localisation = isset($_GET['localisation']) ? trim($_GET['localisation']) : '';
$titre = isset($_GET['titre']) ? trim($_GET['titre']) : '';
$remuneration = isset($_GET['remuneration']) ? trim($_GET['remuneration']) : '';
$duree_offre = isset($_GET['duree_offre']) ? trim($_GET['duree_offre']) : '';
$date_offre = isset($_GET['date_offre']) ? trim($_GET['date_offre']) : '';

// Construction de la requête SQL
$sql = "SELECT * FROM offres WHERE 1=1";
$params = [];

if (!empty($entreprise)) {
    $sql .= " AND entreprise LIKE ?";
    $params[] = "%$entreprise%";
}

if (!empty($competence)) {
    $sql .= " AND competence LIKE ?";
    $params[] = "%$competence%";
}

if (!empty($localisation)) {
    $sql .= " AND localisation LIKE ?";
    $params[] = "%$localisation%";
}

if (!empty($titre)) {
    $sql .= " AND titre LIKE ?";
    $params[] = "%$titre%";
}

if (!empty($remuneration)) {
    $sql .= " AND remuneration >= ?";
    $params[] = $remuneration;
}

if (!empty($duree_offre)) {
    $sql .= " AND duree_offre >= ?";
    $params[] = $duree_offre;
}

if (!empty($date_offre)) {
    switch ($date_offre) {
        case '24h':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case '3j':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
            break;
        case '7j':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case '2s':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)";
            break;
        case '1m':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            break;
        case '2m':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)";
            break;
        case '3m':
            $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            break;
        case 'pi':
            // No filter for date
            break;
    }
}

// Exécution de la requête SQL avec PDO
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liste des offres</title>
    <link rel="stylesheet" href="assets/listes_des_offres.css" />
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
    <h1 class="search-title">Trouve ton offre</h1>

    <form class="login-form" action="listes_des_offres.php" method="GET">
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

        <button type="submit" class="search-button">Rechercher</button>
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
                            <a href="details_offre.php?id=<?= $offre['id'] ?>" class="company-details">Détails de l'offre</a>
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
        <button class="pagination-prev">&lt;- Précédent</button>
        <span class="pagination-current">1/MAX</span>
        <button class="pagination-next">Suivant -&gt;</button>
    </nav>
</main>

<footer class="site-footer">
    <img src="images/logo.png" alt="Logo Footer" class="footer-logo" />
    <nav class="footer-text">
        <a> © 2025 </a>
        <a name="ud_txt" href="A_propos.html"> À propos</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Accesibilite.html"> Accessibilité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Conditions_generales_d'utilisation.html"> Conditions générales d'utilisation</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_de_confidentialité.html"> Politique de confidentialité</a> <a>&nbsp;|&nbsp;</a>
        <a name="ud_txt" href="Politique_relative_aux_cookies.html"> Politique relative aux cookies</a>
    </nav>
</footer>
</body>
</html>
