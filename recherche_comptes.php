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

// Récupérer les informations de l'utilisateur connecté
$stmt = $conn->prepare("SELECT * FROM inscription WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

// Vérifier le rôle de l'utilisateur
$role = isset($_GET['role']) ? $_GET['role'] : '';

// Si l'utilisateur a le rôle admin ou pilote, vérifier que la recherche est valide
if ($user['role'] != 'admin' && $user['role'] != 'pilote' && $role == 'utilisateur') {
    // Un utilisateur avec un rôle non-admin/pilote ne peut pas rechercher des utilisateurs
    echo "Accès interdit.";
    exit();
}

// Si l'utilisateur a le rôle utilisateur, il ne peut rechercher que des pilotes
if ($user['role'] == 'utilisateur' && $role != 'pilote') {
    echo "Accès interdit.";
    exit();
}

// Requête pour récupérer les comptes en fonction du rôle
$query = "SELECT * FROM inscription WHERE role = :role";
$stmt = $conn->prepare($query);
$stmt->bindParam(':role', $role, PDO::PARAM_STR);
$stmt->execute();
$comptes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de comptes</title>
    <link rel="stylesheet" href="assets/profil.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Recherche de comptes <?php echo ucfirst($role); ?></h1>

        <?php if (count($comptes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $compte): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($compte['nom']); ?></td>
                            <td><?php echo htmlspecialchars($compte['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($compte['email']); ?></td>
                            <td><?php echo htmlspecialchars($compte['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($compte['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun compte trouvé pour ce rôle.</p>
        <?php endif; ?>

        <a href="profil.php" class="back-link">Retour au profil</a>
    </div>
</body>
</html>
