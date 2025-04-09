<?php
include 'header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=connexion");
    exit();
}

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

// Récupération des entreprises pour le select
$entreprises = [];
try {
    $stmt = $pdo->query("SELECT id, nom FROM entreprises");
    $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur lors de la récupération des entreprises : " . $e->getMessage() . "</p>";
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entreprise_id = $_POST['entreprise_id'] ?? '';
    $titre = htmlspecialchars($_POST['titre'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $competence = htmlspecialchars($_POST['competence'] ?? '');
    $localisation = htmlspecialchars($_POST['localisation'] ?? '');
    $remuneration = htmlspecialchars($_POST['remuneration'] ?? '');
    $duree_offre = htmlspecialchars($_POST['duree_offre'] ?? '');
    $date_publication = date('Y-m-d'); // Date actuelle

    if (empty($entreprise_id) || empty($titre) || empty($description) || empty($competence) || empty($localisation)) {
        echo "<p style='color: red;'>Tous les champs obligatoires doivent être remplis.</p>";
    } else {
        $logo = '';
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $logo_tmp = $_FILES['logo']['tmp_name'];
            $logo_name = uniqid('logo_') . '_' . basename($_FILES['logo']['name']);
            $logo_path = 'images/' . $logo_name;

            if (move_uploaded_file($logo_tmp, $logo_path)) {
                $logo = $logo_name;
            } else {
                echo "<p style='color: red;'>Erreur lors du téléchargement du logo.</p>";
            }
        }

        $sql = "INSERT INTO offres (
                    entreprise_id, 
                    titre, 
                    description, 
                    date_publication, 
                    competence, 
                    localisation, 
                    remuneration, 
                    duree_offre, 
                    logo
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                $entreprise_id, 
                $titre, 
                $description, 
                $date_publication, 
                $competence, 
                $localisation, 
                $remuneration, 
                $duree_offre, 
                $logo
            ]);
            header("Location: index.php?action=liste_offre");
            exit();
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de l'ajout de l'offre : " . $e->getMessage() . "</p>";
        }
    }
}
?>

<main class="login-content">
    <form class="login-form" action="index.php?action=ajouter_offre" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="entreprise_id">Entreprise :</label>
            <select id="entreprise_id" name="entreprise_id" required>
                <option value="">-- Sélectionner une entreprise --</option>
                <?php foreach ($entreprises as $ent): ?>
                    <option value="<?= htmlspecialchars($ent['id']) ?>">
                        <?= htmlspecialchars($ent['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="titre">Titre de l'offre :</label>
            <input type="text" id="titre" name="titre" required>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="competence">Compétences requises :</label>
            <input type="text" id="competence" name="competence" required>
        </div>

        <div class="form-group">
            <label for="localisation">Localisation :</label>
            <input type="text" id="localisation" name="localisation" required>
        </div>

        <div class="form-group">
            <label for="remuneration">Rémunération :</label>
            <input type="text" id="remuneration" name="remuneration">
        </div>

        <div class="form-group">
            <label for="duree_offre">Durée de l'offre :</label>
            <input type="text" id="duree_offre" name="duree_offre">
        </div>

        <div class="form-group">
            <label for="logo">Logo de l'offre (optionnel) :</label>
            <input type="file" id="logo" name="logo" accept="image/*">
        </div>

        <button type="submit">Ajouter l'offre</button>
    </form>
</main>

<?php include 'footer.php'; ?>
