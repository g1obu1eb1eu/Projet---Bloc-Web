<?php

class OffreModel {
    private $pdo;

    public function getPdo() {
        return $this->pdo;
    }

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

    public function rechercherOffres($filtres) {
        $sql = "SELECT * FROM offres WHERE 1=1";
        $params = [];

        if (!empty($filtres['entreprise'])) {
            $sql .= " AND entreprise LIKE ?";
            $params[] = '%' . $filtres['entreprise'] . '%';
        }

        if (!empty($filtres['competence'])) {
            $sql .= " AND competence LIKE ?";
            $params[] = '%' . $filtres['competence'] . '%';
        }

        if (!empty($filtres['localisation'])) {
            $sql .= " AND localisation LIKE ?";
            $params[] = '%' . $filtres['localisation'] . '%';
        }

        if (!empty($filtres['titre'])) {
            $sql .= " AND titre LIKE ?";
            $params[] = '%' . $filtres['titre'] . '%';
        }

        if (!empty($filtres['remuneration'])) {
            $sql .= " AND remuneration >= ?";
            $params[] = $filtres['remuneration'];
        }

        if (!empty($filtres['duree_offre'])) {
            $sql .= " AND duree_offre >= ?";
            $params[] = $filtres['duree_offre'];
        }

        if (!empty($filtres['date_offre'])) {
            switch ($filtres['date_offre']) {
                case '24h': $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)"; break;
                case '3j':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)"; break;
                case '7j':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"; break;
                case '2s':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)"; break;
                case '1m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"; break;
                case '2m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)"; break;
                case '3m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)"; break;
                case 'pi': default: break;
            }
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOffres($filtres = []) {
        $pdo = $this->getPdo();
        $conditions = [];
        $params = [];

        if (!empty($filtres['entreprise'])) {
            $conditions[] = 'entreprise LIKE :entreprise';
            $params[':entreprise'] = '%' . $filtres['entreprise'] . '%';
        }
        if (!empty($filtres['competence'])) {
            $conditions[] = 'competence LIKE :competence';
            $params[':competence'] = '%' . $filtres['competence'] . '%';
        }
        if (!empty($filtres['localisation'])) {
            $conditions[] = 'localisation LIKE :localisation';
            $params[':localisation'] = '%' . $filtres['localisation'] . '%';
        }
        if (!empty($filtres['titre'])) {
            $conditions[] = 'titre LIKE :titre';
            $params[':titre'] = '%' . $filtres['titre'] . '%';
        }
        if (!empty($filtres['remuneration'])) {
            $conditions[] = 'remuneration >= :remuneration';
            $params[':remuneration'] = $filtres['remuneration'];
        }
        if (!empty($filtres['duree_offre'])) {
            $conditions[] = 'duree_offre <= :duree';
            $params[':duree'] = $filtres['duree_offre'];
        }
        if (!empty($filtres['date_offre']) && $filtres['date_offre'] != 'pi') {
            $intervals = [
                '24h' => '-1 day',
                '3j' => '-3 days',
                '7j' => '-7 days',
                '2s' => '-14 days',
                '1m' => '-1 month',
                '2m' => '-2 months',
                '3m' => '-3 months',
            ];
            if (isset($intervals[$filtres['date_offre']])) {
                $dateLimit = date('Y-m-d H:i:s', strtotime($intervals[$filtres['date_offre']]));
                $conditions[] = 'date_creation >= :date_limit';
                $params[':date_limit'] = $dateLimit;
            }
        }

        $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) FROM offres $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getOffreById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM offres WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterOffre($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO offres (entreprise, titre, competence, localisation, remuneration, duree_offre, date_creation)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['entreprise'],
            $data['titre'],
            $data['competence'],
            $data['localisation'],
            $data['remuneration'],
            $data['duree_offre']
        ]);
    }

    public function modifierOffre($id, $titre, $entreprise, $localisation, $competences, $remuneration, $duree_offre, $description) {
        $sql = "UPDATE offres SET titre = ?, entreprise = ?, localisation = ?, competences = ?, remuneration = ?, duree_offre = ?, description = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$titre, $entreprise, $localisation, $competences, $remuneration, $duree_offre, $description, $id]);
    }
    

    public function supprimerOffre($id) {
        $stmt = $this->pdo->prepare("DELETE FROM offres WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getOffresAvecPagination($filtres, $limit, $offset) {
        $sql = "SELECT * FROM offres WHERE 1=1";
        $params = [];

        if (!empty($filtres['entreprise'])) {
            $sql .= " AND entreprise LIKE ?";
            $params[] = '%' . $filtres['entreprise'] . '%';
        }

        if (!empty($filtres['competence'])) {
            $sql .= " AND competence LIKE ?";
            $params[] = '%' . $filtres['competence'] . '%';
        }

        if (!empty($filtres['localisation'])) {
            $sql .= " AND localisation LIKE ?";
            $params[] = '%' . $filtres['localisation'] . '%';
        }

        if (!empty($filtres['titre'])) {
            $sql .= " AND titre LIKE ?";
            $params[] = '%' . $filtres['titre'] . '%';
        }

        if (!empty($filtres['remuneration'])) {
            $sql .= " AND remuneration >= ?";
            $params[] = $filtres['remuneration'];
        }

        if (!empty($filtres['duree_offre'])) {
            $sql .= " AND duree_offre >= ?";
            $params[] = $filtres['duree_offre'];
        }

        if (!empty($filtres['date_offre'])) {
            switch ($filtres['date_offre']) {
                case '24h': $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)"; break;
                case '3j':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)"; break;
                case '7j':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"; break;
                case '2s':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)"; break;
                case '1m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"; break;
                case '2m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)"; break;
                case '3m':  $sql .= " AND DATE(date_creation) >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)"; break;
                case 'pi': default: break;
            }
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
