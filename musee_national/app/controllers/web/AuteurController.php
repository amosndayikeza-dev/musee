<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\AuteurModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\AuditService;
use App\Services\ExcelExportService;
use App\Middlewares\SessionMiddleware;

class AuteurController extends Controller {
    private $auteurModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        // Admin ou conservateur peuvent gérer les auteurs
        AuthMiddleware::requireAdminOrConservateur();
        $this->auteurModel = new AuteurModel();
    }

    /**
     * Liste des auteurs
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $nationalite = $_GET['nationalite'] ?? '';

        if (!empty($keyword)) {
            $auteurs = $this->auteurModel->search($keyword);
        } elseif (!empty($nationalite)) {
            $auteurs = $this->auteurModel->getByNationalite($nationalite);
        } else {
            $auteurs = $this->auteurModel->getAll();
        }

        $nationalites = $this->auteurModel->getAllNationalites();

        $this->render('index', [
            'auteurs' => $auteurs,
            'keyword' => $keyword,
            'nationalite' => $nationalite,
            'nationalites' => $nationalites,
            'pageTitle' => 'Gestion des Auteurs'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $this->render('create', ['pageTitle' => 'Ajouter un Auteur']);
    }

    /**
     * Enregistrer un nouvel auteur
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/create');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'biographie' => trim($_POST['biographie'] ?? ''),
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'date_deces' => !empty($_POST['date_deces']) ? $_POST['date_deces'] : null, // CORRECTION
            'nationalite' => trim($_POST['nationalite'] ?? '')
        ];

        if (empty($data['nom'])) {
            $this->render('create', [
                'error' => 'Le nom est obligatoire',
                'old' => $data,
                'pageTitle' => 'Ajouter un Auteur'
            ]);
            return;
        }

        $data['matricule'] = $this->auteurModel->generateMatricule();

                // Gestion de l'upload de photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'expositions/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/expositions/' . $filename;
            }
        }

        // 1. Insertion
        $id = $this->auteurModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'auteur', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur ajouté avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->redirect('admin/auteur/index');
            return;
        }
        $this->render('edit', [
            'auteur' => $auteur,
            'pageTitle' => 'Modifier un Auteur'
        ]);
    }

    /**
     * Mettre à jour un auteur
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/edit/' . $id);
            return;
        }

        $old = $this->auteurModel->getById($id);
        if (!$old) {
            $this->redirect('admin/auteur/index');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'biographie' => trim($_POST['biographie'] ?? ''),
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'date_deces' => !empty($_POST['date_deces']) ? $_POST['date_deces'] : null,
            'nationalite' => trim($_POST['nationalite'] ?? '')
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Le nom est obligatoire';
            $this->redirect('admin/auteur/edit/' . $id);
            return;
        }

        // Gestion de l'upload de photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'auteurs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/expositions/' . $filename;
            }
        }

        // 1. Mise à jour
        $this->auteurModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'auteur', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur modifié avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Supprimer un auteur (Admin uniquement)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/index');
            return;
        }

        $old = $this->auteurModel->getById($id);
        if (!$old) {
            $this->redirect('admin/auteur/index');
            return;
        }

        // Vérifier si l'auteur a des œuvres associées
        $oeuvresAssociees = $this->auteurModel->getOeuvres($id);
        if (!empty($oeuvresAssociees)) {
            $_SESSION['error'] = 'Impossible de supprimer cet auteur car il est associé à ' . count($oeuvresAssociees) . ' œuvre(s).';
            $this->redirect('admin/auteur/index');
            return;
        }

        // 1. Suppression
        $this->auteurModel->delete($id); // CORRECTION : un seul appel

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'auteur', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur supprimé avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Détail d'un auteur
     */
    public function showAction($id) {
        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->redirect('admin/auteur/index');
            return;
        }
        $oeuvres = $this->auteurModel->getOeuvres($id);
        $this->render('show', [
            'auteur' => $auteur,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Détails de l\'Auteur'
        ]);
    }

    /**
     * Recherche AJAX
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $results = [];
        if (!empty($keyword)) {
            $results = $this->auteurModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * Export PDF
     */
    /**
 * Export PDF de la liste des auteurs (avec filtres)
 */
public function exportPdfAction() {
    // Récupérer les filtres depuis l'URL
    $keyword = $_GET['keyword'] ?? '';
    $nationalite = $_GET['nationalite'] ?? '';

    // Appliquer les mêmes filtres que dans indexAction()
    if (!empty($keyword)) {
        $auteurs = $this->auteurModel->search($keyword);
    } elseif (!empty($nationalite)) {
        $auteurs = $this->auteurModel->getByNationalite($nationalite);
    } else {
        $auteurs = $this->auteurModel->getAll();
    }

    // Générer le HTML pour le PDF
    $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Auteurs</h1>';
    $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';

    // Afficher les filtres appliqués
    if (!empty($keyword)) {
        $html .= '<p style="text-align:center; color:#555;">Recherche : "' . htmlspecialchars($keyword) . '"</p>';
    } elseif (!empty($nationalite)) {
        $html .= '<p style="text-align:center; color:#555;">Nationalité : "' . htmlspecialchars($nationalite) . '"</p>';
    }

    $html .= '<hr>';
    $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px; font-family: Arial, sans-serif;">';
    $html .= '<thead>
                <tr style="background:#1a2a3a; color:white; font-weight:bold;">
                    <th style="padding:8px; text-align:left;">Matricule</th>
                    <th style="padding:8px; text-align:left;">Nom</th>
                    <th style="padding:8px; text-align:left;">Prénom</th>
                    <th style="padding:8px; text-align:left;">Nationalité</th>
                    <th style="padding:8px; text-align:left;">Date naissance</th>
                    <th style="padding:8px; text-align:left;">Date décès</th>
                </tr>
              </thead><tbody>';

    if (empty($auteurs)) {
        $html .= '<tr><td colspan="6" style="text-align:center; padding:20px; color:#999;">Aucun auteur trouvé</td></tr>';
    } else {
        foreach ($auteurs as $auteur) {
            $html .= '<tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:8px;">' . $auteur->matricule . '</td>
                        <td style="padding:8px;">' . htmlspecialchars($auteur->nom) . '</td>
                        <td style="padding:8px;">' . htmlspecialchars($auteur->prenom ?? '') . '</td>
                        <td style="padding:8px;">' . htmlspecialchars($auteur->nationalite ?? '') . '</td>
                        <td style="padding:8px;">' . ($auteur->date_naissance ?? '') . '</td>
                        <td style="padding:8px;">' . ($auteur->date_deces ?? '') . '</td>
                      </tr>';
        }
    }
    $html .= '</tbody></table>';

    $pdfService = new PdfExportService();
    $pdfService->generateFromHtml($html, 'liste_auteurs', 'landscape');
}

/**
 * Export Excel de la liste des auteurs (avec filtres)
 */
public function exportExcelAction() {
    // Récupérer les filtres depuis l'URL
    $keyword = $_GET['keyword'] ?? '';
    $nationalite = $_GET['nationalite'] ?? '';

    // Appliquer les mêmes filtres
    if (!empty($keyword)) {
        $auteurs = $this->auteurModel->search($keyword);
    } elseif (!empty($nationalite)) {
        $auteurs = $this->auteurModel->getByNationalite($nationalite);
    } else {
        $auteurs = $this->auteurModel->getAll();
    }

    $headers = ['Matricule', 'Nom', 'Prénom', 'Nationalité', 'Date naissance', 'Date décès'];
    $data = [];
    foreach ($auteurs as $auteur) {
        $data[] = [
            $auteur->matricule,
            $auteur->nom,
            $auteur->prenom ?? '',
            $auteur->nationalite ?? '',
            $auteur->date_naissance ?? '',
            $auteur->date_deces ?? ''
        ];
    }

    $excel = new ExcelExportService();
    $excel->export($data, $headers, 'auteurs_' . date('Y-m-d'), 'Liste des auteurs');
}


    //
    /**
 * Affiche la corbeille des auteurs supprimés
 */
public function trashAction() {
    $items = $this->auteurModel->getTrashed();
    $this->render('trash', [
        'items' => $items,
        'pageTitle' => 'Corbeille - Auteurs'
    ]);
}

/**
 * Restaure un auteur depuis la corbeille
 */
public function restoreAction($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('admin/auteur/trash');
        return;
    }
    $this->auteurModel->restore($id);
    $_SESSION['success'] = 'Auteur restauré avec succès !';
    $this->redirect('admin/auteur/trash');
}

/**
 * Supprime définitivement un auteur
 */
public function forceDeleteAction($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('admin/auteur/trash');
        return;
    }
    $this->auteurModel->forceDelete($id);
    $_SESSION['success'] = 'Auteur supprimé définitivement !';
    $this->redirect('admin/auteur/trash');
}

/**
 * Vide la corbeille des auteurs
 */
public function emptyTrashAction() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('admin/auteur/trash');
        return;
    }
    $this->auteurModel->forceDeleteAllTrashed();
    $_SESSION['success'] = 'Corbeille vidée avec succès !';
    $this->redirect('admin/auteur/trash');
}
}