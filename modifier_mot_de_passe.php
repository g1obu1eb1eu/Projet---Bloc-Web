<?php
session_start();

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
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

    // Vérifier si le nouveau mot de passe et sa confirmation correspondent
    if ($nouveau_mot_de_passe !== $confirmation_mot_de_passe) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Vérifier l'ancien mot de passe
    $stmt = $conn->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($ancien_mot_de_passe, $user['mot_de_passe'])) {
        // L'ancien mot de passe est correct, on peut mettre à jour le mot de passe
        $hashed_new_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);

        // Requête pour mettre à jour le mot de passe dans la base de données
        $updateStmt = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE id = :id");
        $updateStmt->bindParam(':mot_de_passe', $hashed_new_password);
        $updateStmt->bindParam(':id', $_SESSION['user_id']);
        $updateStmt->execute();

        echo "Mot de passe modifié avec succès.";
    } else {
        echo "Ancien mot de passe incorrect.";
    }
}
?>
