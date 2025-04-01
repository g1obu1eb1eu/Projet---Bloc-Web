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

// Fonction pour éviter les warnings si une colonne est manquante
function getValue($array, $key) {
    return isset($array[$key]) ? htmlspecialchars($array[$key]) : "";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
</head>
<body>
    <h1>Bienvenue, <?php echo getValue($user, 'prenom') . " " . getValue($user, 'nom'); ?> !</h1>
    
    <h2>Informations personnelles</h2>
    <p><strong>Nom : </strong><?php echo getValue($user, 'nom'); ?></p>
    <p><strong>Prénom : </strong><?php echo getValue($user, 'prenom'); ?></p>
    <p><strong>Email : </strong><?php echo getValue($user, 'email'); ?> (non modifiable)</p>
    <p><strong>Téléphone : </strong><?php echo getValue($user, 'telephone'); ?></p>
    <p><strong>Date de naissance : </strong><?php echo getValue($user, 'date_de_naissance'); ?></p>
    <p><strong>Genre : </strong><?php echo getValue($user, 'genre'); ?></p>
    <p><strong>Durée du stage : </strong><?php echo getValue($user, 'duree_stage'); ?></p>
    <p><strong>Localité : </strong><?php echo getValue($user, 'localite'); ?></p>

    <h2>Modifier mes informations</h2>
    <form method="POST" action="modifier_infos.php" enctype="multipart/form-data">
        <div>
            <label for="nom">Nom</label>
            <input type="text" name="nom" value="<?php echo getValue($user, 'nom'); ?>" required />
        </div>
        <div>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" value="<?php echo getValue($user, 'prenom'); ?>" required />
        </div>
        <div>
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" value="<?php echo getValue($user, 'telephone'); ?>" required />
        </div>
        <div>
            <label for="date_naissance">Date de naissance</label>
            <input type="date" name="date_naissance" value="<?php echo getValue($user, 'date_naissance'); ?>" required />
        </div>
        <div>
            <label for="genre">Genre</label>
            <select name="genre" required>
                <option value="Homme" <?php echo (getValue($user, 'genre') == "Homme") ? "selected" : ""; ?>>Homme</option>
                <option value="Femme" <?php echo (getValue($user, 'genre') == "Femme") ? "selected" : ""; ?>>Femme</option>
                <option value="Autre" <?php echo (getValue($user, 'genre') == "Autre") ? "selected" : ""; ?>>Autre</option>
            </select>
        </div>
        <div>
            <label for="duree_stage">Durée du stage</label>
            <input type="text" name="duree_stage" value="<?php echo getValue($user, 'duree_stage'); ?>" required />
        </div>
        <div>
            <label for="localite">Localité</label>
            <input type="text" name="localite" value="<?php echo getValue($user, 'localite'); ?>" required />
        </div>
        <div>
            <label for="cv">Déposer un CV (PDF uniquement)</label>
            <input type="file" name="cv" accept=".pdf" />
        </div>
        <button type="submit">Modifier mes informations</button>
    </form>

    <h2>Modifier mon mot de passe</h2>
    <form method="POST" action="modifier_mot_de_passe.php">
        <div>
            <label for="ancien_mot_de_passe">Ancien mot de passe</label>
            <input type="password" name="ancien_mot_de_passe" required />
        </div>
        <div>
            <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
            <input type="password" name="nouveau_mot_de_passe" required />
        </div>
        <div>
            <label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
            <input type="password" name="confirmation_mot_de_passe" required />
        </div>
        <button type="submit">Modifier le mot de passe</button>
    </form>

    <a href="deconnexion.php">Se déconnecter</a>
</body>
</html>
