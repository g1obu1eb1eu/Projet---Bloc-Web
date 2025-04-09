<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'utilisateurs'; // Nom de ta base de données
$username = 'root';       // Utilisateur de la base de données
$password = '';           // Mot de passe pour MySQL (vide pour XAMPP par défaut)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des données du formulaire
$entreprise = $_POST['entreprise'];
$competence = $_POST['competence'];
$localisation = $_POST['localisation'];
$titre = $_POST['titre'];
$description = $_POST['description'];
$remuneration = $_POST['remuneration'];
$duree_offre = $_POST['duree_offre'];

// Gestion du téléchargement du logo
$logo = '';
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
    $logo_tmp = $_FILES['logo']['tmp_name'];
    $logo_name = basename($_FILES['logo']['name']);
    $logo_path = 'images/' . $logo_name;  // Chemin où tu souhaites stocker le logo

    if (move_uploaded_file($logo_tmp, $logo_path)) {
        $logo = $logo_name;
    } else {
        echo "Erreur lors du téléchargement du fichier.";
    }
}

// Insertion des données dans la base de données
$sql = "INSERT INTO offres (entreprise_id, titre, description, date_publication, entreprise, competence, localisation, remuneration, duree_offre, logo) 
        VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

// Assure-toi que l'entreprise avec ID=1 existe dans la table `entreprises`
$entreprise_id = 1; // Exemple: L'ID de l'entreprise doit être valide

// Insère les valeurs, y compris l'ID de l'entreprise
$stmt->execute([$entreprise_id, $titre, $description, $entreprise, $competence, $localisation, $remuneration, $duree_offre, $logo]);

echo "Offre ajoutée avec succès !";
?>
