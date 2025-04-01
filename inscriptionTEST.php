<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'utilisateurs';
$username = 'root'; // Modifier selon ton environnement
$password = ''; // Modifier selon ton environnement

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire avec sécurisation
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $date_de_naissance = $_POST['date_de_naissance'];
    $genre = htmlspecialchars(trim($_POST['genre']));
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérification des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($date_de_naissance) || empty($genre) || empty($mot_de_passe)) {
        die("Tous les champs sont obligatoires !");
    }

    // Vérification de l'email valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email invalide !");
    }

    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        die("Cet email est déjà utilisé !");
    }

    // Hachage du mot de passe
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, date_de_naissance, genre, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$nom, $prenom, $email, $telephone, $date_de_naissance, $genre, $mot_de_passe_hash]);

    if ($success) {
        header("Location: page_de_connexion.html");
        exit(); // S'assurer que la redirection s'exécute correctement
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>
