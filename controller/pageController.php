<?php
    class PageController {
        public function renderPage($page) {
            
            switch ($page) {
                case 'home':
                    include 'view/home.php';
                    break;
                case 'connexion':
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $this->handleConnexion();
                    } else {
                        include 'view/connexion.php';
                    }
                    break;
                case 'inscription':
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $this->handleInscription();
                    } else {
                        include 'view/inscription.php';
                    }
                    break;
                case 'profil':
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $this->handleProfile();
                    } else {
                        include 'view/profil.php';
                    }
                    break;
                case 'liste_entreprise':
                    require_once 'model/EntrepriseModel.php';
                    $entrepriseModel = new EntrepriseModel();
                
                    $motCle = isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : '';
                    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
                
                    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 6; 
                    $offset = ($page - 1) * $limit;
                
                    $entreprises = $entrepriseModel->rechercherEntreprises($motCle, $location, $limit, $offset);
                    $totalEntreprises = $entrepriseModel->countEntreprises($motCle, $location);
                    $totalPages = ceil($totalEntreprises / $limit);
                
                    include 'view/liste_entreprise.php';
                    break;                                      
                case 'ajouter_entreprise':
                    include 'view/ajouter_entreprise.php';
                    break;
                case 'details_entreprise':
                    session_start();

                    require_once 'model/EntrepriseModel.php';
                    $entrepriseModel = new EntrepriseModel();
                
                    $user_id = $_SESSION['user_id'] ?? null;
                
                    $id = $_GET['id'] ?? null;
                
                    if (!$id) {
                        echo "ID d'entreprise manquant.";
                        break;
                    }
                
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['submit_comment'])){
                            $commentaire = trim($_POST['commentaire']);
                            
                            // DEBUG 
                            // echo "<pre>";
                            // var_dump([
                            //     'entreprise_id' => $id,
                            //     'user_id' => $user_id,
                            //     'commentaire' => $commentaire
                            // ]);
                            // echo "</pre>";
                            // exit;
                                                       
                            $prenom_utilisateur = $_SESSION['user_prenom'] ?? '';
                            $nom_utilisateur = $_SESSION['user_name'] ?? '';

                            $entrepriseModel->ajouterCommentaire($id, $user_id, $commentaire, $nom_utilisateur, $prenom_utilisateur);

                        }
                
                        if (isset($_POST['submit_rating'])) {
                            $entrepriseModel->ajouterEvaluation($id, $user_id, $_POST['rating']);
                            header("Location: index.php?action=details_entreprise&id=$id");
                            exit();
                        }
                
                        if (isset($_POST['add_to_wishlist']) && $user_id) {
                            $entrepriseModel->ajouterWishlist($id, $user_id);
                        }
                
                        header("Location: index.php?action=details_entreprise&id=$id");
                        exit;
                    }
                
                    $entreprise = $entrepriseModel->getEntrepriseById($id);
                    $average_rating = $entrepriseModel->getAverageRating($id);
                    $commentaires = $entrepriseModel->getCommentaires($entrepriseModel->getPdo(), $id);
                    $in_wishlist = $user_id ? $entrepriseModel->isInWishlist($id, $user_id) : false;
                
                    include 'view/details_entreprise.php';
                    break;                    
                case 'liste_offre':
                    require_once 'model/OffreModel.php';
                    $offreModel = new OffreModel();

                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $offset = ($page - 1) * $limit;

                    $filtres = [
                        'entreprise' => $_GET['entreprise'] ?? '',
                        'competence' => $_GET['competence'] ?? '',
                        'localisation' => $_GET['localisation'] ?? '',
                        'titre' => $_GET['titre'] ?? '',
                        'remuneration' => $_GET['remuneration'] ?? '',
                        'duree_offre' => $_GET['duree_offre'] ?? '',
                        'date_offre' => $_GET['date_offre'] ?? '',
                    ];

                    $entreprise = $filtres['entreprise'];
                    $competence = $filtres['competence'];
                    $localisation = $filtres['localisation'];
                    $titre = $filtres['titre'];
                    $remuneration = $filtres['remuneration'];
                    $duree_offre = $filtres['duree_offre'];
                    $date_offre = $filtres['date_offre'];

                    $totalOffres = $offreModel->countOffres($filtres);
                    $totalPages = ceil($totalOffres / $limit);

                    $offres = $offreModel->rechercherOffres($filtres, $limit, $offset);

                    require 'view/liste_offre.php';
                    break;
                case 'ajouter_offre':
                    include 'view/ajouter_offre.php';
                    break;   
                case 'details_offre':
                    session_start();
                
                    require_once 'model/OffreModel.php';
                    $offreModel = new OffreModel();
                
                    $user_id = $_SESSION['user_id'] ?? null;
                    $id = $_GET['id'] ?? null;
                
                    if (!$id) {
                        echo "ID de l'offre manquant.";
                        break;
                    }
                
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'modifier_offre') {
                        // Récupérer les données du formulaire
                        $id = $_GET['id'];
                        $titre = $_POST['titre'];
                        $entreprise = $_POST['entreprise'];
                        $localisation = $_POST['localisation'];
                        $competences = $_POST['competences'];
                        $remuneration = $_POST['remuneration'];
                        $duree_offre = $_POST['duree_offre'];
                        $description = $_POST['description'];
                    
                        // Appeler la méthode pour modifier l'offre
                        $offreModel->modifierOffre($id, $titre, $entreprise, $localisation, $competences, $remuneration, $duree_offre, $description);
                    
                        // Rediriger ou afficher un message de succès
                        header("Location: details_offre.php?id=" . $id);
                        exit;
                    }
                    
                
                    $offre = $offreModel->getOffreById($id);
                
                    if (!$offre) {
                        echo "Offre introuvable.";
                        break;
                    }
                
                    include 'view/details_offre.php';
                    break;             
                case 'politique_confidentialite':
                    include 'view/politique_confidentialite.php';
                    break;
                case 'condition_generales_d_utilisation':
                    include 'view/condition_generales_d_utilisation.php';
                    break;
                case 'accessibilite':
                    include 'view/accessibilite.php';
                    break;        
                case 'cookie':
                    include 'view/cookie.php';
                    break;
                case 'a_propos':
                    include 'view/a_propos.php';
                    break;
                case 'logout':
                    $this->handleLogout();
                    break;
                case'wishlist':
                    include 'view/wishlist.php';
                    break;
                default:
                    include 'view/home.php';
                    break;
            }
        }

        private function handleInscription() {
            session_start();
            
            $host = 'localhost';
            $dbname = 'utilisateurs';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }

            $nom = htmlspecialchars(trim($_POST['nom']));
            $prenom = htmlspecialchars(trim($_POST['prenom']));
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $telephone = htmlspecialchars(trim($_POST['telephone']));
            $date_de_naissance = $_POST['date_de_naissance'];
            $genre = htmlspecialchars(trim($_POST['genre']));
            $mot_de_passe = $_POST['mot_de_passe'];

            if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($date_de_naissance) || empty($genre) || empty($mot_de_passe)) {
                die("Tous les champs sont obligatoires !");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Email invalide !");
            }

            $stmt = $pdo->prepare("SELECT id FROM inscription WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                die("Cet email est déjà utilisé !");
            }

            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO inscription (nom, prenom, email, telephone, date_de_naissance, genre, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $success = $stmt->execute([$nom, $prenom, $email, $telephone, $date_de_naissance, $genre, $mot_de_passe_hash]);

            if ($success) {
                header("Location: index.php?action=home");
                exit();
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }

        private function handleConnexion(){
            session_start();
        
            $host = "localhost";
            $user = "root"; 
            $pass = "";  
            $dbname = "utilisateurs"; 
        
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connexion échouée : " . $e->getMessage());
            }
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $mot_de_passe = $_POST['mot_de_passe'];
        
                $stmt = $conn->prepare("SELECT id, nom, prenom, mot_de_passe, role FROM inscription WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id = $user['id'];
                    $nom = $user['nom'];
                    $prenom = $user['prenom']; 
                    $hashed_password = $user['mot_de_passe'];
                    $role = $user['role']; 
                    
                    if (password_verify($mot_de_passe, $hashed_password)) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $nom;
                        $_SESSION['user_prenom'] = $prenom; 
                        $_SESSION['role'] = $role; 
        
                        header("Location: index.php?action=home");
                        exit();
                    } else {
                        echo "Mot de passe incorrect.";
                    }
                } else {
                    echo "Aucun utilisateur trouvé avec cet email.";
                }
                $conn = null; 
            }
        }        
        
        private function handleLogout() {
            session_start();
            session_destroy(); 
            header("Location: index.php?action=home"); 
            exit();
        }
            
        
    }