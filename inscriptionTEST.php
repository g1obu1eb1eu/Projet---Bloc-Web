<?php
// Connexion à la base de données
$host = "localhost";
$user = "root";  // Par défaut sous XAMPP, l'utilisateur est "root"
$pass = "";  // Par défaut, pas de mot de passe
$dbname = "utilisateurs"; // Nom de la base de données

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupération des données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hachage du mot de passe
$prenom = $_POST['prenom'];
$telephone = $_POST['telephone'];
$date_de_naissance = $_POST['date_de_naissance'];
$genre = $_POST['genre'];



// Vérifier si l'email existe déjà
$sql_check = "SELECT id FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "Email déjà utilisé.";
    $stmt_check->close();
    $conn->close();
    exit(); // Arrête le script ici
}
$stmt_check->close();

// Préparation de la requête SQL
$stmt = $conn->prepare("INSERT INTO users (nom, email, mot_de_passe, prenom, telephone, date_de_naissance, genre) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiss", $nom, $email, $mot_de_passe, $prenom, $telephone, $date_de_naissance, $genre);

if ($stmt->execute()) {
    // Redirection vers la page de connexion après inscription
    header("Location: page_de_connexion.html");
    exit(); // Arrête le script après la redirection
} else {
    echo "Erreur : " . $stmt->error;
}


// Fermer la connexion
$stmt->close();
$conn->close();
?>
