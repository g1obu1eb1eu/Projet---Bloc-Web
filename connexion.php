<?php
// Connexion à la base de données
$host = "localhost";
$user = "root"; // Par défaut sous XAMPP, l'utilisateur est "root"
$pass = "";  // Par défaut, pas de mot de passe
$dbname = "utilisateurs"; // Nom de la base de données

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour vérifier l'utilisateur
    $stmt = $conn->prepare("SELECT id, nom, mot_de_passe FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // L'utilisateur existe, récupérer le mot de passe haché
        $stmt->bind_result($id, $nom, $hashed_password);
        $stmt->fetch();

        // Vérifier le mot de passe
        if (password_verify($mot_de_passe, $hashed_password)) {
            // Connexion réussie, démarrer la session
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nom;

            // Redirection vers la page d'accueil ou tableau de bord
            header("Location: page_d_accueil.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    $stmt->close();
    $conn->close();
}
?>
