<div class="container">
    <h2><i class="fas fa-comment-dots"></i> Messagerie</h2>
    
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 20px; margin-top: 20px;">
        <!-- Liste des utilisateurs -->
        <div style="background: #fff; border-radius: 8px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <h4>Contacts</h4>
            <ul id="userList" style="list-style: none; padding: 0;">
                <?php foreach ($users as $user): ?>
                    <li style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" 
                        onclick="loadChat(<?= $user->id ?>)">
                        <?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?>
                        <?php if ($user->non_lus > 0): ?>
                            <span style="background: #dc3545; color: #fff; border-radius: 50%; padding: 2px 8px; font-size: 11px; float: right;">
                                <?= $user->non_lus ?>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Zone de conversation -->
        <div style="background: #fff; border-radius: 8px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <div id="chatMessages" style="height: 400px; overflow-y: auto; border-bottom: 1px solid #eee; margin-bottom: 15px; padding: 10px;">
                <p style="color: #999; text-align: center;">Sélectionnez un contact pour commencer à discuter.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="chatInput" placeholder="Votre message..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <button onclick="sendMessage()" class="btn btn-primary">Envoyer</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentUserId = null;
    
    function loadChat(userId) {
        currentUserId = userId;
        fetch('<?= BASE_URL ?>chat/messages/' + userId)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('chatMessages');
                container.innerHTML = '';
                if (data.messages) {
                    data.messages.forEach(msg => {
                        const div = document.createElement('div');
                        div.style.margin = '5px 0';
                        div.style.textAlign = (msg.expediteur_id == <?= $_SESSION['user_id'] ?>) ? 'right' : 'left';
                        div.innerHTML = '<strong>' + msg.expediteur_prenom + ' ' + msg.expediteur_nom + '</strong>: ' + msg.message + '<br><small style="color:#888;">' + new Date(msg.date_envoi).toLocaleString() + '</small>';
                        container.appendChild(div);
                    });
                    container.scrollTop = container.scrollHeight;
                }
            });
    }
    
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message || !currentUserId) return;
        
        fetch('<?= BASE_URL ?>chat/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ destinataire_id: currentUserId, message: message })
        }).then(() => {
            input.value = '';
            loadChat(currentUserId);
        });
    }
    
    document.getElementById('chatInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
</script>