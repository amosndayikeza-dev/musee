// Toggle sidebar en mobile
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });

        // Fermer le sidebar si on clique à l'extérieur (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }
});



// Recherche AJAX pour les œuvres
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="keyword"]');
    const searchForm = searchInput?.closest('form');
    
    if (searchInput && searchForm) {
        let typingTimer;
        const doneTyping = function() {
            searchForm.submit();
        };
        
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            if (this.value.length >= 2 || this.value.length === 0) {
                typingTimer = setTimeout(doneTyping, 500);
            }
        });
    }
});