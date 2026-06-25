<?php
namespace App\Services;

class UploadService {
    
    /**
     * Upload une photo de profil
     */
    public function uploadProfilPhoto($file, $userId) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        
        // Vérifier le type
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Format de fichier non supporté. Utilisez JPG, PNG, GIF ou WEBP.';
            return false;
        }
        
        // Vérifier la taille
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Le fichier est trop volumineux (max 2 Mo)';
            return false;
        }
        
        // Créer le dossier si nécessaire
        $uploadDir = UPLOAD_DIR . 'profils/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Générer un nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/profils/' . $filename;
        }
        
        $_SESSION['error'] = 'Erreur lors du téléversement';
        return false;
    }
}