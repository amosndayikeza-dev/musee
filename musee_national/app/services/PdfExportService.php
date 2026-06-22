<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfExportService {
    
    /**
     * Génère un PDF à partir d'un tableau de données et d'une vue HTML
     * @param string $html Le contenu HTML à convertir
     * @param string $filename Nom du fichier (sans extension)
     * @param string $orientation 'portrait' ou 'landscape'
     */
    public function generateFromHtml($html, $filename = 'export', $orientation = 'portrait') {
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Pour autoriser les images distantes
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation);
        $dompdf->render();
        
        // Envoi du fichier au navigateur
        $dompdf->stream($filename . '.pdf', [
            'Attachment' => 1 // 0 pour ouvrir dans le navigateur, 1 pour forcer le téléchargement
        ]);
        exit;
    }
}