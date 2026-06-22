<?php
namespace App\Services;

use App\Core\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportService {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Génère un rapport périodique
     */
    public function generatePeriodicReport($type, $dateDebut, $dateFin) {
        $data = [];
        
        switch ($type) {
            case 'oeuvres':
                $data = $this->getOeuvresReport($dateDebut, $dateFin);
                break;
            case 'prets':
                $data = $this->getPretsReport($dateDebut, $dateFin);
                break;
            case 'expositions':
                $data = $this->getExpositionsReport($dateDebut, $dateFin);
                break;
        }
        
        return $data;
    }

    private function getOeuvresReport($dateDebut, $dateFin) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'exposé' THEN 1 ELSE 0 END) as exposes,
                    SUM(CASE WHEN statut = 'en réserve' THEN 1 ELSE 0 END) as reserve,
                    SUM(CASE WHEN statut = 'en restauration' THEN 1 ELSE 0 END) as restauration,
                    SUM(CASE WHEN statut = 'en prêt' THEN 1 ELSE 0 END) as pret
                FROM oeuvre
                WHERE date_creation BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateDebut, $dateFin]);
        return $stmt->fetch();
    }

    private function getPretsReport($dateDebut, $dateFin) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'en cours' THEN 1 ELSE 0 END) as en_cours,
                    SUM(CASE WHEN statut = 'retourné' THEN 1 ELSE 0 END) as retournes,
                    SUM(CASE WHEN date_fin < CURDATE() AND statut = 'en cours' THEN 1 ELSE 0 END) as retard
                FROM prets
                WHERE date_debut BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateDebut, $dateFin]);
        return $stmt->fetch();
    }

    private function getExpositionsReport($dateDebut, $dateFin) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'prévue' THEN 1 ELSE 0 END) as prevues,
                    SUM(CASE WHEN statut = 'en cours' THEN 1 ELSE 0 END) as en_cours,
                    SUM(CASE WHEN statut = 'terminée' THEN 1 ELSE 0 END) as terminees
                FROM expositions
                WHERE date_debut BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateDebut, $dateFin]);
        return $stmt->fetch();
    }

    /**
     * Génère un rapport personnalisé (choix des colonnes)
     */
    public function generateCustomReport($table, $columns, $filters = []) {
        $sql = "SELECT " . implode(', ', $columns) . " FROM $table WHERE 1=1";
        $params = [];
        
        foreach ($filters as $key => $value) {
            $sql .= " AND $key = ?";
            $params[] = $value;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Exporte un rapport en PDF
     */
    public function exportPDF($data, $title, $headers) {
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        $html = $this->generateReportHTML($data, $title, $headers);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $dompdf->stream($title . '.pdf', ['Attachment' => 1]);
        exit;
    }

    private function generateReportHTML($data, $title, $headers) {
        $html = '<h1 style="text-align:center;">' . $title . '</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">';
        $html .= '<thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th style="background:#1a2a3a; color:white;">' . $header . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ((array)$row as $value) {
                $html .= '<td>' . $value . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
}