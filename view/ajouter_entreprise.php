<?php
include 'header.php';

if (isset($_SESSION['role']) && ($_SESSION['role'] !== 'admin')) {
    header("Location: index.php?action=connexion");
    exit();
}

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $ville = htmlspecialchars($_POST['ville'] ?? '');
    $code_postal = htmlspecialchars($_POST['code_postal'] ?? '');

    if (empty($nom) || empty($description) || empty($ville) || empty($code_postal)) {
        echo "<p style='color: red;'>Tous les champs doivent être remplis.</p>";
    } else {
        $logo = '';
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $logo_tmp = $_FILES['logo']['tmp_name'];
            $logo_name = uniqid('logo_') . '_' . basename($_FILES['logo']['name']); // éviter les collisions
            $logo_path = 'images/' . $logo_name;

            if (move_uploaded_file($logo_tmp, $logo_path)) {
                $logo = $logo_name;
            } else {
                echo "<p style='color: red;'>Erreur lors du téléchargement du fichier.</p>";
            }
        }

        $sql = "INSERT INTO entreprises (nom, description, ville, code_postal, logo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$nom, $description, $ville, $code_postal, $logo]);
            header("Location: index.php?action=liste_entreprise");
            exit();
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de l'ajout : " . $e->getMessage() . "</p>";
        }
    }
}

?>

<main class="login-content">
    <form class="login-form" action="index.php?action=ajouter_entreprise" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">Nom de l'entreprise :</label>
            <input type="text" id="nom" name="nom" required><br>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description"></textarea><br>
        </div> 

        <div class="form-group">
            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" required><br>
        </div>

        <div class="form-group">
            <label for="code_postal">Code postal :</label>
            <input type="text" id="code_postal" name="code_postal" required pattern="\d{5}" title="Entrez un code postal valide"><br>
        </div>

        <div class="form-group">    
            <label for="logo">Logo de l'entreprise :</label>
            <input type="file" id="logo" name="logo" accept="image/*"><br>
        </div>

        <button type="submit">Ajouter l'entreprise</button>
    </form>
</main>

<?php include 'footer.php'; ?>
