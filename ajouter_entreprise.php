<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
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

// Récupération des données du formulaire
$nom = $_POST['nom'];
$description = $_POST['description'];
$ville = $_POST['ville'];
$code_postal = $_POST['code_postal'];

// Gestion du téléchargement du logo
$logo = '';
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
    $logo_tmp = $_FILES['logo']['tmp_name'];
    $logo_name = basename($_FILES['logo']['name']);
    $logo_path = 'images/' . $logo_name;

    if (move_uploaded_file($logo_tmp, $logo_path)) {
        $logo = $logo_name;
    } else {
        echo "Erreur lors du téléchargement du fichier.";
    }
}

// Insertion des données dans la base de données
$sql = "INSERT INTO entreprises (nom, description, ville, code_postal, logo) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nom, $description, $ville, $code_postal, $logo]);

echo "Entreprise ajoutée avec succès !";
?>
