<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService {
    private $dompdf;

    public function __construct() {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    public function generate($html, $filename = 'document.pdf') {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    public function download($html, $filename = 'document.pdf') {
        $output = $this->generate($html, $filename);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        echo $output;
        exit;
    }

    public function stream($html, $filename = 'document.pdf') {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        $this->dompdf->stream($filename);
        exit;
    }
}