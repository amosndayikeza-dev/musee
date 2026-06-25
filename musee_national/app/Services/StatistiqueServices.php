<?php
namespace App\Services;

use App\Core\Database;

class StatistiqueService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Nombre total d'œuvres
     */
    public function getTotalOeuvres() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM oeuvre");
        return $stmt->fetch()->total;
    }

    /**
     * Nombre d'œuvres par statut
     */
    public function getOeuvresParStatut() {
        $stmt = $this->db->query("
            SELECT statut, COUNT(*) as total 
            FROM oeuvre 
            GROUP BY statut
        ");
        return $stmt->fetchAll();
    }

    /**
     * Nombre d'œuvres par catégorie (pour graphique)
     */
    public function getOeuvresParCategorie() {
        $stmt = $this->db->query("
            SELECT c.nom as categorie, COUNT(o.id) as total 
            FROM categorie c
            LEFT JOIN oeuvre o ON c.id = o.categorie_id
            GROUP BY c.id, c.nom
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Nombre d'œuvres par auteur (top 10)
     */
    public function getOeuvresParAuteur($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT CONCAT(a.nom, ' ', a.prenom) as auteur, COUNT(o.id) as total 
            FROM auteurs a
            LEFT JOIN oeuvre o ON a.id = o.auteur_id
            GROUP BY a.id
            ORDER BY total DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Nombre d'expositions par statut
     */
    public function getExpositionsParStatut() {
        $stmt = $this->db->query("
            SELECT statut, COUNT(*) as total 
            FROM expositions 
            GROUP BY statut
        ");
        return $stmt->fetchAll();
    }

    /**
     * Expositions à venir (prochaines 5)
     */
    public function getProchainesExpositions($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT * FROM expositions 
            WHERE date_debut > CURDATE() 
            ORDER BY date_debut ASC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Prêts en cours
     */
    public function getPretsEnCours() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM prets 
            WHERE statut = 'en cours'
        ");
        return $stmt->fetch()->total;
    }

    /**
     * Prêts en retard
     */
    public function getPretsEnRetard() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM prets 
            WHERE statut = 'en cours' AND date_fin < CURDATE()
        ");
        return $stmt->fetch()->total;
    }

    /**
     * Restaurations en cours
     */
    public function getRestaurationsEnCours() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM restauration 
            WHERE date_fin IS NULL OR date_fin > CURDATE()
        ");
        return $stmt->fetch()->total;
    }

    /**
     * Coût total des restaurations
     */
    public function getCoutTotalRestaurations() {
        $stmt = $this->db->query("
            SELECT SUM(cout) as total 
            FROM restauration
        ");
        return $stmt->fetch()->total ?? 0;
    }

    /**
     * Mouvements récents (entrées/sorties)
     */
    public function getMouvementsRecents($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT m.*, o.titre as oeuvre_titre 
            FROM mouvement m
            JOIN oeuvre o ON m.oeuvre_id = o.id
            ORDER BY m.date DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Statistiques globales pour le dashboard
     */
    public function getDashboardStats() {
        return [
            'total_oeuvres' => $this->getTotalOeuvres(),
            'prets_en_cours' => $this->getPretsEnCours(),
            'prets_en_retard' => $this->getPretsEnRetard(),
            'restaurations_en_cours' => $this->getRestaurationsEnCours(),
            'cout_restaurations' => $this->getCoutTotalRestaurations(),
            'oeuvres_par_statut' => $this->getOeuvresParStatut(),
            'oeuvres_par_categorie' => $this->getOeuvresParCategorie(),
            'expositions_par_statut' => $this->getExpositionsParStatut(),
            'prochaines_expositions' => $this->getProchainesExpositions(),
            'mouvements_recents' => $this->getMouvementsRecents(),
        ];
    }
}