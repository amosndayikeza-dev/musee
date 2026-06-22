<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\UtilisateurModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\ExcelExportService;

class UtilisateurController extends Controller {
    private $utilisateurModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté ET est admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('auth/login');
        }
        // Seul l'admin peut gérer les utilisateurs
        AuthMiddleware::requireAdmin();
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * Liste des utilisateurs
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        
        if (!empty($keyword)) {
            $utilisateurs = $this->utilisateurModel->search($keyword);
        } else {
            $utilisateurs = $this->utilisateurModel->getAllWithRoles();
        }
        
        $this->render('index', [
            'utilisateurs' => $utilisateurs,
            'keyword' => $keyword,
            'pageTitle' => 'Gestion des Utilisateurs'
        ]);
    }

    /**
     * Formulaire d'ajout d'un utilisateur
     */
    public function createAction() {
        $this->render('create', [
            'pageTitle' => 'Ajouter un utilisateur'
        ]);
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('utilisateur/create');
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
            'role' => $_POST['role'] ?? 'visiteur'
        ];

        // Validation
        if (empty($data['nom']) || empty($data['email']) || empty($data['mot_de_passe'])) {
            $this->render('create', [
                'error' => 'Le nom, l\'email et le mot de passe sont obligatoires',
                'old' => $data,
                'pageTitle' => 'Ajouter un utilisateur'
            ]);
            return;
        }

        // Vérifier l'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->render('create', [
                'error' => 'Email invalide',
                'old' => $data,
                'pageTitle' => 'Ajouter un utilisateur'
            ]);
            return;
        }

        // Vérifier si l'email existe déjà
        if ($this->utilisateurModel->emailExists($data['email'])) {
            $this->render('create', [
                'error' => 'Cet email est déjà utilisé',
                'old' => $data,
                'pageTitle' => 'Ajouter un utilisateur'
            ]);
            return;
        }

        // Vérifier la longueur du mot de passe
        if (strlen($data['mot_de_passe']) < 6) {
            $this->render('create', [
                'error' => 'Le mot de passe doit contenir au moins 6 caractères',
                'old' => $data,
                'pageTitle' => 'Ajouter un utilisateur'
            ]);
            return;
        }

        $this->utilisateurModel->create($data);
        $_SESSION['success'] = "L'utilisateur a été créé avec succès !";
        $this->redirect('utilisateur/index');
    }

    /**
     * Formulaire d'édition d'un utilisateur
     */
    public function editAction($id) {
        $utilisateur = $this->utilisateurModel->getById($id);
        if (!$utilisateur) {
            $this->redirect('utilisateur/index');
        }
        
        // Empêcher l'édition de son propre compte depuis cette interface
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas modifier votre propre compte depuis cette interface.";
            $this->redirect('utilisateur/index');
        }
        
        $this->render('edit', [
            'utilisateur' => $utilisateur,
            'pageTitle' => 'Modifier un utilisateur'
        ]);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('utilisateur/edit/' . $id);
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'visiteur'
        ];

        // Si un nouveau mot de passe est fourni
        if (!empty($_POST['mot_de_passe'])) {
            if (strlen($_POST['mot_de_passe']) < 6) {
                $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères';
                $this->redirect('utilisateur/edit/' . $id);
                return;
            }
            $data['mot_de_passe'] = $_POST['mot_de_passe'];
        }

        // Validation
        if (empty($data['nom']) || empty($data['email'])) {
            $_SESSION['error'] = 'Le nom et l\'email sont obligatoires';
            $this->redirect('utilisateur/edit/' . $id);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide';
            $this->redirect('utilisateur/edit/' . $id);
            return;
        }

        // Vérifier si l'email existe déjà (sauf pour cet utilisateur)
        if ($this->utilisateurModel->emailExists($data['email'], $id)) {
            $_SESSION['error'] = 'Cet email est déjà utilisé par un autre utilisateur';
            $this->redirect('utilisateur/edit/' . $id);
            return;
        }

        // Empêcher la modification de son propre rôle (sécurité)
        if ($id == $_SESSION['user_id']) {
            unset($data['role']);
        }

        $this->utilisateurModel->updateUser($id, $data);
        $_SESSION['success'] = "L'utilisateur a été modifié avec succès !";
        $this->redirect('utilisateur/index');
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('utilisateur/index');
        }

        // Empêcher la suppression de son propre compte
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            $this->redirect('utilisateur/index');
        }

        // Empêcher la suppression du dernier administrateur
        if ($_SESSION['role'] === 'admin') {
            $admins = $this->utilisateurModel->countAdmins();
            $user = $this->utilisateurModel->getById($id);
            if ($user && $user->role === 'admin' && $admins <= 1) {
                $_SESSION['error'] = "Impossible de supprimer le dernier administrateur.";
                $this->redirect('utilisateur/index');
            }
        }

        $this->utilisateurModel->delete($id);
        $_SESSION['success'] = "L'utilisateur a été supprimé avec succès !";
        $this->redirect('utilisateur/index');
    }

    /**
     * Voir le détail d'un utilisateur
     */
    public function showAction($id) {
        $utilisateur = $this->utilisateurModel->getById($id);
        if (!$utilisateur) {
            $this->redirect('utilisateur/index');
        }
        $this->render('show', [
            'utilisateur' => $utilisateur,
            'pageTitle' => 'Détail de l\'utilisateur'
        ]);
    }

    /**
     * Export PDF de la liste des utilisateurs
     */
    public function exportPdfAction() {
        $utilisateurs = $this->utilisateurModel->getAllWithRoles();
        
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Utilisateurs</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date création</th>
                        <th>Dernier accès</th>
                    </tr>
                  </thead><tbody>';
        foreach ($utilisateurs as $user) {
            $html .= '<tr>
                        <td>' . $user->id . '</td>
                        <td>' . htmlspecialchars($user->nom) . '</td>
                        <td>' . htmlspecialchars($user->prenom ?? '') . '</td>
                        <td>' . htmlspecialchars($user->email) . '</td>
                        <td>' . $user->role . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($user->date_creation)) . '</td>
                        <td>' . ($user->dernier_acces ? date('d/m/Y H:i', strtotime($user->dernier_acces)) : 'Jamais') . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_utilisateurs', 'landscape');
    }

public function exportExcelAction() {
    $utilisateurs = $this->utilisateurModel->getAllWithRoles();
    
    $headers = ['ID', 'Nom', 'Prénom', 'Email', 'Rôle', 'Date création', 'Dernier accès'];
    $data = [];
    foreach ($utilisateurs as $user) {
        $data[] = [
            $user->id,
            $user->nom,
            $user->prenom ?? '',
            $user->email,
            $user->role,
            date('d/m/Y H:i', strtotime($user->date_creation)),
            $user->dernier_acces ? date('d/m/Y H:i', strtotime($user->dernier_acces)) : 'Jamais'
        ];
    }
    
    $excel = new ExcelExportService();
    $excel->export($data, $headers, 'utilisateurs_' . date('Y-m-d'), 'Liste des utilisateurs');
}
}