<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com'; // À configurer
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'votre-email@gmail.com'; // À configurer
        $this->mail->Password = 'votre-mot-de-passe'; // À configurer
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        $this->mail->setFrom('noreply@devopsamos@gmail.com', 'Musée National');
        $this->mail->isHTML(true);
    }

    /**
     * Envoie un email
     */
    public function send($to, $subject, $body) {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notification de prêt en retard
     */
   public function notifyPretRetard($pret, $oeuvre, $adminEmail = null) {
    // Utiliser l'email passé ou un email par défaut
    $adminEmail = $adminEmail ?? 'admin@musee.com';
    
    $subject = "🔔 Alerte : Prêt en retard - " . $oeuvre->titre;
    $body = "
        <h2>Prêt en retard</h2>
        <p>L'œuvre <strong>" . $oeuvre->titre . "</strong> est actuellement en prêt et doit être retournée.</p>
        <ul>
            <li><strong>Emprunteur :</strong> " . $pret->emprunteur . "</li>
            <li><strong>Date de retour prévue :</strong> " . date('d/m/Y', strtotime($pret->date_fin)) . "</li>
            <li><strong>Retard :</strong> " . (new \DateTime($pret->date_fin))->diff(new \DateTime())->days . " jours</li>
        </ul>
        <p>Merci de prendre les mesures nécessaires.</p>
    ";
    return $this->send($adminEmail, $subject, $body);
}
}