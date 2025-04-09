    <?php
    class EntrepriseModel {
        private $pdo;

        public function __construct() {
            $host = 'localhost';
            $dbname = 'utilisateurs';
            $username = 'root';
            $password = '';

            try {
                $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        public function rechercherEntreprises($motCle = '', $location = '', $limit = 6, $offset = 0) {
            $sql = "SELECT * FROM entreprises WHERE 1=1";
            $params = [];
        
            if (!empty($motCle)) {
                $sql .= " AND nom LIKE :motcle";
                $params[':motcle'] = "%$motCle%";
            }
        
            if (!empty($location)) {
                $sql .= " AND (ville LIKE :location OR code_postal LIKE :location)";
                $params[':location'] = "%$location%";
            }
        
            $sql .= " LIMIT :limit OFFSET :offset";
        
            $stmt = $this->pdo->prepare($sql);
        
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function countEntreprises($motCle = '', $location = '') {
            $sql = "SELECT COUNT(*) FROM entreprises WHERE 1=1";
            $params = [];
        
            if (!empty($motCle)) {
                $sql .= " AND nom LIKE :motcle";
                $params[':motcle'] = "%$motCle%";
            }
        
            if (!empty($location)) {
                $sql .= " AND (ville LIKE :location OR code_postal LIKE :location)";
                $params[':location'] = "%$location%";
            }
        
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        }

        public function getEntrepriseById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM entreprises WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function getAverageRating($entreprise_id) {
            $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg FROM evaluations WHERE entreprise_id = ?");
            $stmt->execute([$entreprise_id]);
            return $stmt->fetchColumn();
        }
        
        public function isInWishlist($entreprise_id, $user_id) {
            $stmt = $this->pdo->prepare("SELECT 1 FROM wishlist WHERE entreprise_id = ? AND user_id = ?");
            $stmt->execute([$entreprise_id, $user_id]);
            return $stmt->fetch() !== false;
        }
        
        public function ajouterEvaluation($entreprise_id, $user_id, $rating) {
            $stmt = $this->pdo->prepare("INSERT INTO evaluations (entreprise_id, user_id, rating) VALUES (?, ?, ?)");
            $stmt->execute([$entreprise_id, $user_id, $rating]);
        }
        
        public function ajouterCommentaire($entreprise_id, $user_id, $commentaire, $nom, $prenom)
        {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO commentaires (entreprise_id, user_id, commentaire, nom_utilisateur, prenom_utilisateur)
                    VALUES (:entreprise_id, :user_id, :commentaire, :nom_utilisateur, :prenom_utilisateur)
                ");

                $stmt->bindParam(':entreprise_id', $entreprise_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                $stmt->bindParam(':nom_utilisateur', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom_utilisateur', $prenom, PDO::PARAM_STR);

                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
                throw new Exception("Impossible d'ajouter le commentaire.");
            }
        }
        
        function getCommentaires($pdo, $entreprise_id) {
            $sql = "SELECT commentaire, nom_utilisateur, prenom_utilisateur FROM commentaires WHERE entreprise_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$entreprise_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function ajouterWishlist($entreprise_id, $user_id) {
            $stmt = $this->pdo->prepare("INSERT INTO wishlist (entreprise_id, user_id) VALUES (?, ?)");
            $stmt->execute([$entreprise_id, $user_id]);
        }

        public function getPdo() {
            return $this->pdo;
        }
        
    }