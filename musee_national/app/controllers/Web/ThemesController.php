<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ThemeModel;
use App\Middlewares\AuthMiddleware;
use App\Services\AuditService;
use App\Middlewares\SessionMiddleware;

class ThemesController extends Controller {
    
    private $themeModel;

    public function __construct() {
        SessionMiddleware::check();
        AuthMiddleware::requireAdmin();
        $this->themeModel = new ThemeModel();
    }

    public function indexAction() {
        $themes = $this->themeModel->getAll();
        $activeTheme = $this->themeModel->getActive();

        $this->render('index', [
            'themes' => $themes,
            'activeTheme' => $activeTheme,
            'pageTitle' => 'Gestion des thèmes'
        ]);
    }

    public function createAction() {
        $this->render('create', [
            'pageTitle' => 'Ajouter un thème'
        ]);
    }

    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/themes/create');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'couleur_primaire' => $_POST['couleur_primaire'] ?? '#1a2a3a',
            'couleur_secondaire' => $_POST['couleur_secondaire'] ?? '#c9a84c',
            'couleur_fond' => $_POST['couleur_fond'] ?? '#f4f6f9',
            'couleur_texte' => $_POST['couleur_texte'] ?? '#333333',
            'actif' => $_POST['actif'] ?? 0
        ];

        if (empty($data['nom'])) {
            $this->render('create', [
                'error' => 'Le nom est obligatoire',
                'old' => $data,
                'pageTitle' => 'Ajouter un thème'
            ]);
            return;
        }

        // Si le thème est activé, désactiver les autres
        if ($data['actif'] == 1) {
            $this->themeModel->activate(0); // désactive tous les thèmes
        }

        $id = $this->themeModel->insert($data);

        // Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'theme', $id, null, $data);

        $_SESSION['success'] = 'Thème ajouté avec succès !';
        $this->redirect('admin/themes');
    }

    public function editAction($id) {
        $theme = $this->themeModel->getById($id);
        if (!$theme) {
            $this->redirect('admin/themes');
            return;
        }
        $this->render('edit', [
            'theme' => $theme,
            'pageTitle' => 'Modifier un thème'
        ]);
    }

    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/themes/edit/' . $id);
            return;
        }

        $old = $this->themeModel->getById($id);
        if (!$old) {
            $this->redirect('admin/themes');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'couleur_primaire' => $_POST['couleur_primaire'] ?? '#1a2a3a',
            'couleur_secondaire' => $_POST['couleur_secondaire'] ?? '#c9a84c',
            'couleur_fond' => $_POST['couleur_fond'] ?? '#f4f6f9',
            'couleur_texte' => $_POST['couleur_texte'] ?? '#333333',
            'actif' => $_POST['actif'] ?? 0
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Le nom est obligatoire';
            $this->redirect('admin/themes/edit/' . $id);
            return;
        }

        // Si le thème est activé, désactiver les autres
        if ($data['actif'] == 1) {
            $this->themeModel->activate(0); // désactive tous les thèmes
        }

        $this->themeModel->update($id, $data);

        // Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'theme', $id, (array)$old, $data);

        $_SESSION['success'] = 'Thème modifié avec succès !';
        $this->redirect('admin/themes');
    }

    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/themes');
            return;
        }

        $old = $this->themeModel->getById($id);
        if (!$old) {
            $this->redirect('admin/themes');
            return;
        }

        // Soft delete (le modèle doit utiliser SoftDeleteTrait)
        $result = $this->themeModel->delete($id);
        if (!$result) {
            $_SESSION['error'] = 'Impossible de supprimer le thème actif.';
            $this->redirect('admin/themes');
            return;
        }

        // Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'theme', $id, (array)$old, null);

        $_SESSION['success'] = 'Thème supprimé avec succès !';
        $this->redirect('admin/themes');
    }

    public function showAction($id) {
        $theme = $this->themeModel->getById($id);
        if (!$theme) {
            $this->redirect('admin/themes');
            return;
        }
        $this->render('show', [
            'theme' => $theme,
            'pageTitle' => 'Détail du thème'
        ]);
    }

    public function activateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->themeModel->getActive();
            $this->themeModel->activate($id);

            // Audit
            $audit = new AuditService();
            $audit->log('UPDATE', 'theme', $id, (array)$old, ['actif' => 1]);

            $_SESSION['success'] = 'Thème activé avec succès !';
        }
        $this->redirect('admin/themes');
    }
}