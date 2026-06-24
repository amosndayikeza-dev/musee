<div class="container">
    <h2><i class="fas fa-comment-dots"></i> Messagerie</h2>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 25px; margin-top: 20px;">
        <!-- ===== LISTE DES CONTACTS ===== -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="background: var(--couleur-primaire, #1a2a3a); color: #fff; padding: 15px 20px; font-weight: 600; border-bottom: 2px solid var(--couleur-secondaire, #c9a84c);">
                <i class="fas fa-users"></i> Contacts
                <span class="badge" style="background: var(--couleur-secondaire); color: #fff; float: right; margin-top: 2px;">
                    <?= count($users) ?>
                </span>
            </div>
            <ul id="userList" style="list-style: none; padding: 0; margin: 0; max-height: 500px; overflow-y: auto;">
                <?php foreach ($users as $user): ?>
                    <li style="padding: 12px 20px; border-bottom: 1px solid #f0f0f0; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;"
                        onclick="loadChat(<?= $user->id ?>)"
                        onmouseover="this.style.background='#f8f9fa'"
                        onmouseout="this.style.background=''">
                        <?php if (!empty($user->photo)): ?>
                            <img src="<?= BASE_URL . $user->photo ?>" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--couleur-secondaire);">
                        <?php else: ?>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--couleur-secondaire); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold;">
                                <?= strtoupper(substr($user->prenom ?? $user->nom, 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div style="flex: 1;">
                            <div style="font-weight: 500;"><?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?></div>
                            <div style="font-size: 12px; color: #888;"><?= htmlspecialchars($user->email) ?></div>
                        </div>
                        <?php if ($user->non_lus > 0): ?>
                            <span class="badge" style="background: #dc3545; color: #fff; border-radius: 50%; padding: 2px 10px; font-size: 12px;">
                                <?= $user->non_lus ?>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- ===== ZONE DE CONVERSATION ===== -->
        <div class="card" style="padding: 0; display: flex; flex-direction: column; height: 550px;">
            <!-- En-tête de la conversation -->
            <div id="chatHeader" style="background: var(--couleur-primaire, #1a2a3a); color: #fff; padding: 15px 20px; border-bottom: 2px solid var(--couleur-secondaire, #c9a84c); display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-user-circle" style="font-size: 28px;"></i>
                <span id="contactName" style="font-weight: 500;">Sélectionnez un contact</span>
            </div>

            <!-- Messages -->
            <div id="chatMessages" style="flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa;">
                <p style="text-align: center; color: #999; margin-top: 40%;">Sélectionnez un contact pour commencer à discuter.</p>
            </div>

            <!-- Zone de saisie -->
            <div style="padding: 15px 20px; border-top: 1px solid #e9ecef; background: #fff; display: flex; gap: 10px; align-items: center;">
                <input type="text" id="chatInput" class="form-control" placeholder="Votre message..." style="flex: 1; padding: 10px 14px; border-radius: 20px;">
                <label for="fileInput" style="cursor: pointer; color: #888; padding: 8px 12px; border-radius: 50%; background: #f1f3f5; transition: 0.2s;"
                       onmouseover="this.style.background='#e9ecef'"
                       onmouseout="this.style.background='#f1f3f5'">
                    <i class="fas fa-paperclip"></i>
                </label>
                <input type="file" id="fileInput" style="display: none;" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.mp3,.mp4,.wav">
                <button onclick="sendMessage()" class="btn btn-primary" style="border-radius: 20px; padding: 10px 20px;">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentUserId = null;
    let currentUserName = '';

    function loadChat(userId) {
        currentUserId = userId;
        // Récupérer le nom du contact
        const li = document.querySelector(`#userList li[onclick="loadChat(${userId})"]`);
        if (li) {
            const nameDiv = li.querySelector('div:first-child div:first-child');
            currentUserName = nameDiv ? nameDiv.textContent : 'Contact';
            document.getElementById('contactName').textContent = currentUserName;
        }

        fetch('<?= BASE_URL ?>chat/messages/' + userId)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('chatMessages');
                container.innerHTML = '';
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const isMe = (msg.expediteur_id == <?= $_SESSION['user_id'] ?>);
                        const div = document.createElement('div');
                        div.style.margin = '8px 0';
                        div.style.display = 'flex';
                        div.style.flexDirection = 'column';
                        div.style.alignItems = isMe ? 'flex-end' : 'flex-start';

                        const bubble = document.createElement('div');
                        bubble.style.maxWidth = '70%';
                        bubble.style.padding = '10px 16px';
                        bubble.style.borderRadius = '18px';
                        bubble.style.wordWrap = 'break-word';
                        bubble.style.backgroundColor = isMe ? 'var(--couleur-secondaire, #c9a84c)' : '#fff';
                        bubble.style.color = isMe ? '#fff' : '#333';
                        bubble.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
                        bubble.style.border = isMe ? 'none' : '1px solid #e9ecef';

                        let html = (msg.message || '');
                        if (msg.fichier) {
                            if (msg.type_fichier && msg.type_fichier.startsWith('image/')) {
                                html += `<br><img src="<?= BASE_URL ?>${msg.fichier}" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 5px;">`;
                            } else {
                                html += `<br><a href="<?= BASE_URL ?>${msg.fichier}" target="_blank" style="color: ${isMe ? '#fff' : '#c9a84c'}; text-decoration: underline;">📎 Télécharger le fichier</a>`;
                            }
                        }
                        bubble.innerHTML = html;

                        const time = document.createElement('div');
                        time.style.fontSize = '10px';
                        time.style.color = isMe ? 'rgba(255,255,255,0.7)' : '#999';
                        time.style.marginTop = '4px';
                        time.textContent = new Date(msg.date_envoi).toLocaleString();

                        div.appendChild(bubble);
                        div.appendChild(time);
                        container.appendChild(div);
                    });
                    container.scrollTop = container.scrollHeight;
                } else {
                    container.innerHTML = '<p style="text-align: center; color: #999; margin-top: 40%;">Aucun message. Commencez la conversation !</p>';
                }
            });
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const fileInput = document.getElementById('fileInput');
        const message = input.value.trim();
        const file = fileInput.files[0];

        if (!message && !file) return;
        if (!currentUserId) {
            alert('Veuillez sélectionner un contact.');
            return;
        }

        const formData = new FormData();
        if (message) formData.append('message', message);
        if (file) formData.append('fichier', file);
        formData.append('destinataire_id', currentUserId);

        fetch('<?= BASE_URL ?>chat/send', {
            method: 'POST',
            body: formData
        }).then(() => {
            input.value = '';
            fileInput.value = '';
            loadChat(currentUserId);
        });
    }

    document.getElementById('chatInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    // Rafraîchissement automatique toutes les 5 secondes
    setInterval(() => {
        if (currentUserId) {
            loadChat(currentUserId);
        }
    }, 5000);
</script>