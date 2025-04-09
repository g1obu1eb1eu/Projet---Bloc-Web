<?php
    include 'header.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "utilisateurs"; 

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connexion échouée : " . $e->getMessage());
    }

    $stmt = $conn->prepare("SELECT * FROM inscription WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit();
    }

    function getValue($array, $key) {
        return isset($array[$key]) ? htmlspecialchars($array[$key]) : "";
    }
?>

    <div class="profile-container">
        <div class="profile-card">
            <h1 class="profile-title">Bienvenue, <?php echo htmlspecialchars($user['prenom']) . " " . htmlspecialchars($user['nom']); ?> !</h1>

            <h2>Informations personnelles</h2>
            <ul class="profile-info">
                <li><strong>Nom :</strong> <?php echo getValue($user, 'nom'); ?></li>
                <li><strong>Prénom :</strong> <?php echo getValue($user, 'prenom'); ?></li>
                <li><strong>Email :</strong> <?php echo getValue($user, 'email'); ?> (non modifiable)</li>
                <li><strong>Téléphone :</strong> <?php echo getValue($user, 'telephone'); ?></li>
                <li><strong>Date de naissance :</strong> <?php echo getValue($user, 'date_de_naissance'); ?></li>
                <li><strong>Genre :</strong> <?php echo getValue($user, 'genre'); ?></li>
                <li><strong>Durée du stage :</strong> <?php echo getValue($user, 'duree_stage'); ?></li>
                <li><strong>Localité :</strong> <?php echo getValue($user, 'localite'); ?></li>
            </ul>
        </div>

        <a href="index.php?action=wishlist">
            <button class="logout-button">Afficher la wish-list</button>
        </a>
        <br>

        <div class="profile-form">
            <h2>Modifier mes informations</h2>
            <form method="POST" action="modifier_infos.php" enctype="multipart/form-data">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="<?php echo getValue($user, 'nom'); ?>" required />

                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" value="<?php echo getValue($user, 'prenom'); ?>" required />

                <label for="telephone">Téléphone</label>
                <input type="text" name="telephone" value="<?php echo getValue($user, 'telephone'); ?>" required />

                <label for="date_naissance">Date de naissance</label>
                <input type="date" name="date_naissance" value="<?php echo getValue($user, 'date_naissance'); ?>" required />

                <label for="genre">Genre</label>
                <select name="genre" required>
                    <option value="Homme" <?php echo (getValue($user, 'genre') == "Homme") ? "selected" : ""; ?>>Homme</option>
                    <option value="Femme" <?php echo (getValue($user, 'genre') == "Femme") ? "selected" : ""; ?>>Femme</option>
                    <option value="Autre" <?php echo (getValue($user, 'genre') == "Autre") ? "selected" : ""; ?>>Autre</option>
                </select>

                <label for="duree_stage">Durée du stage</label>
                <input type="text" name="duree_stage" value="<?php echo getValue($user, 'duree_stage'); ?>" required />

                <label for="localite">Localité</label>
                <input type="text" name="localite" value="<?php echo getValue($user, 'localite'); ?>" required />

                <label for="cv">Déposer un CV (PDF uniquement)</label>
                <input type="file" name="cv" accept=".pdf" />

                <button type="submit">Modifier mes informations</button>
            </form>
        </div>

        <div class="profile-form">
            <h2>Modifier mon mot de passe</h2>
            <form method="POST" action="modifier_mot_de_passe.php">
                <label for="ancien_mot_de_passe">Ancien mot de passe</label>
                <input type="password" name="ancien_mot_de_passe" required />

                <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
                <input type="password" name="nouveau_mot_de_passe" required />

                <label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
                <input type="password" name="confirmation_mot_de_passe" required />

                <button type="submit">Modifier le mot de passe</button>
            </form>
        </div>

        <a href="index.php?action=logout">
            <button class="logout-button">Se déconnecter</button>
        </a>
    </div>


<?php include 'footer.php'; ?>
