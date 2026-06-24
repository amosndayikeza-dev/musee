<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-envelope"></i> Détail du message</h3>
        <div>
            <a href="<?= BASE_URL ?>admin/messages" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
    </div>
    <div style="padding:20px;">
        <p><strong>De :</strong> <?= htmlspecialchars($message->nom) ?> &lt;<?= htmlspecialchars($message->email) ?>&gt;</p>
        <p><strong>Sujet :</strong> <?= htmlspecialchars($message->sujet) ?></p>
        <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($message->date_envoi)) ?></p>
        <hr>
        <p><strong>Message :</strong></p>
        <div style="background:#f8f9fa; padding:15px; border-radius:8px; white-space:pre-wrap;"><?= nl2br(htmlspecialchars($message->message)) ?></div>

        <div style="margin-top:20px;">
            <a href="mailto:<?= htmlspecialchars($message->email) ?>?subject=Re: <?= urlencode($message->sujet) ?>&body=Bonjour <?= urlencode($message->nom) ?>,%0A%0A<?= urlencode('Merci pour votre message. Voici ma réponse :') ?>%0A%0A" class="btn btn-gold">
                <i class="fas fa-reply"></i> Répondre
            </a>
            <form method="post" action="<?= BASE_URL ?>admin/messages/delete/<?= $message->id ?>" style="display:inline;">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce message ?')">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>