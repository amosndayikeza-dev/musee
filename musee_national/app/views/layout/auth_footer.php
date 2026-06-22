        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus sur le premier champ
            const emailField = document.getElementById('email');
            if (emailField && !emailField.value) {
                emailField.focus();
            }

            // Animation sur le bouton
            const loginBtn = document.querySelector('.btn-login');
            const form = document.getElementById('loginForm');

            if (form) {
                form.addEventListener('submit', function() {
                    if (loginBtn) {
                        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
                        loginBtn.disabled = true;
                    }
                });
            }

            // Afficher/masquer le mot de passe
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            
            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>