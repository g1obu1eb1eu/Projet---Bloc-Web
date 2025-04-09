<?php
// Connexion à la base de données
$host = "localhost";
$user = "root"; // Par défaut sous XAMPP, l'utilisateur est "root"
$pass = "";  // Par défaut, pas de mot de passe
$dbname = "utilisateurs"; // Nom de la base de données

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Définir le mode d'erreur de PDO sur exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour vérifier l'utilisateur
    $stmt = $conn->prepare("SELECT id, nom, mot_de_passe FROM inscription WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // L'utilisateur existe, récupérer le mot de passe haché
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $user['id'];
        $nom = $user['nom'];
        $hashed_password = $user['mot_de_passe'];

        // Vérifier le mot de passe
        if (password_verify($mot_de_passe, $hashed_password)) {
            // Connexion réussie, démarrer la session
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nom;

            // Redirection vers la page d'accueil ou tableau de bord
            header("Location: index.php?action=home");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
    $conn = null; // Fermer la connexion PDO
}
?>
