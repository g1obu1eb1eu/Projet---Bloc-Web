<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "utilisateurs"; // Nom de la base de données

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $duree_stage = $_POST['duree_stage'];
    $localite = $_POST['localite'];

    // Requête pour mettre à jour les informations dans la base de données
    $updateStmt = $conn->prepare("UPDATE inscription SET
        nom = :nom,
        prenom = :prenom,
        telephone = :telephone,
        date_de_naissance = :date_de_naissance,
        genre = :genre,
        duree_stage = :duree_stage,
        localite = :localite
        WHERE id = :id");

    // Assurez-vous que chaque paramètre est lié correctement
    $updateStmt->bindParam(':nom', $nom);
    $updateStmt->bindParam(':prenom', $prenom);
    $updateStmt->bindParam(':telephone', $telephone);
    $updateStmt->bindParam(':date_de_naissance', $date_naissance);
    $updateStmt->bindParam(':genre', $genre);
    $updateStmt->bindParam(':duree_stage', $duree_stage);
    $updateStmt->bindParam(':localite', $localite);
    $updateStmt->bindParam(':id', $_SESSION['user_id']);

    if ($updateStmt->execute()) {
        echo "Informations mises à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour des informations.";
    }

    // Définir le chemin absolu vers le répertoire uploads
    $uploadDirectory = __DIR__ . '/uploads/';

    // Vérifier si le répertoire uploads existe, sinon le créer
    if (!is_dir($uploadDirectory)) {
        if (!mkdir($uploadDirectory, 0755, true)) {
            die('Erreur : Impossible de créer le répertoire uploads.');
        }
    }

    // Gestion du téléchargement du CV
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == UPLOAD_ERR_OK) {
        $cv_name = basename($_FILES['cv']['name']);
        $cv_tmp_name = $_FILES['cv']['tmp_name'];
        $cv_path = $uploadDirectory . $cv_name;

        if (move_uploaded_file($cv_tmp_name, $cv_path)) {
            // Mettre à jour le chemin du CV dans la base de données
            $cvUpdateStmt = $conn->prepare("UPDATE inscription SET cv_path = :cv_path WHERE id = :id");
            $cvUpdateStmt->bindParam(':cv_path', $cv_path);
            $cvUpdateStmt->bindParam(':id', $_SESSION['user_id']);
            $cvUpdateStmt->execute();
            echo "CV téléchargé et mis à jour avec succès.";
        } else {
            echo "Erreur lors du téléchargement du CV.";
        }
    }
}
?>
