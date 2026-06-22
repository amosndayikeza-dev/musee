/**
 * Dashboard - Statistiques et graphiques
 * Ce fichier est chargé uniquement sur la page /admin/dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // 1. Animation des KPI (compteurs)
    // ============================================
    const kpiValues = document.querySelectorAll('.kpi-value');
    kpiValues.forEach(element => {
        const target = parseInt(element.textContent.replace(/\s/g, ''), 10);
        if (!isNaN(target) && target > 0) {
            animateCounter(element, target);
        }
    });

    function animateCounter(element, target) {
        let current = 0;
        const step = Math.max(1, Math.floor(target / 30)); // 30 étapes
        const interval = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(interval);
            }
            element.textContent = current;
        }, 40);
    }

  /**
 * Dashboard.js - Graphiques professionnels avec Chart.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que Chart.js est chargé
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js non chargé');
        return;
    }

    // ============================================
    // 1. Graphique : Répartition par statut (camembert)
    // ============================================
    const ctxStatut = document.getElementById('chartStatut');
    if (ctxStatut && typeof statsStatut !== 'undefined' && statsStatut.length > 0) {
        const colors = ['#28a745', '#6c757d', '#fd7e14', '#007bff', '#dc3545'];
        new Chart(ctxStatut, {
            type: 'pie',
            data: {
                labels: statsStatut.map(item => item.statut || 'Non défini'),
                datasets: [{
                    data: statsStatut.map(item => parseInt(item.total) || 0),
                    backgroundColor: colors.slice(0, statsStatut.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // ============================================
    // 2. Graphique : Œuvres par catégorie (barres)
    // ============================================
    const ctxCategorie = document.getElementById('chartCategorie');
    if (ctxCategorie && typeof statsCategorie !== 'undefined' && statsCategorie.length > 0) {
        new Chart(ctxCategorie, {
            type: 'bar',
            data: {
                labels: statsCategorie.map(item => item.categorie || 'Non classé'),
                datasets: [{
                    label: 'Nombre d\'œuvres',
                    data: statsCategorie.map(item => parseInt(item.total) || 0),
                    backgroundColor: 'rgba(201, 168, 76, 0.7)',
                    borderColor: 'rgba(201, 168, 76, 1)',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // ============================================
    // 3. Graphique : Top 5 auteurs (barres horizontales)
    // ============================================
    const ctxAuteurs = document.getElementById('chartAuteurs');
    if (ctxAuteurs && typeof statsAuteurs !== 'undefined' && statsAuteurs.length > 0) {
        new Chart(ctxAuteurs, {
            type: 'bar',
            data: {
                labels: statsAuteurs.map(item => (item.prenom ? item.prenom + ' ' : '') + item.nom),
                datasets: [{
                    label: 'Nombre d\'œuvres',
                    data: statsAuteurs.map(item => parseInt(item.total) || 0),
                    backgroundColor: 'rgba(26, 42, 58, 0.7)',
                    borderColor: 'rgba(26, 42, 58, 1)',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // ============================================
    // 4. Graphique : Mouvements (barres)
    // ============================================
    const ctxMouvements = document.getElementById('chartMouvements');
    if (ctxMouvements && typeof entrees !== 'undefined' && typeof sorties !== 'undefined') {
        new Chart(ctxMouvements, {
            type: 'bar',
            data: {
                labels: ['Entrées', 'Sorties'],
                datasets: [{
                    label: 'Nombre de mouvements',
                    data: [parseInt(entrees) || 0, parseInt(sorties) || 0],
                    backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                    borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});

/**
 * Dashboard.js - Fonctions pour le tableau de bord professionnel
 * Auteur: Musée National
 * Version: 1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ============================================
    // 1. ANIMATION DES CHIFFRES (KPI)
    // ============================================
    function animateNumbers() {
        const kpiValues = document.querySelectorAll('.kpi-value');
        
        kpiValues.forEach(element => {
            const target = parseInt(element.getAttribute('data-target') || element.textContent.replace(/[^0-9.]/g, ''));
            if (isNaN(target)) return;
            
            const duration = 1000; // ms
            const startTime = performance.now();
            const startValue = 0;
            
            function updateValue(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Fonction d'accélération (easeOut)
                const eased = 1 - Math.pow(1 - progress, 3);
                const currentValue = Math.round(startValue + (target - startValue) * eased);
                
                // Formater le nombre (ajouter des séparateurs si besoin)
                element.textContent = currentValue.toLocaleString('fr-FR');
                
                if (progress < 1) {
                    requestAnimationFrame(updateValue);
                } else {
                    element.textContent = target.toLocaleString('fr-FR');
                }
            }
            
            // Sauvegarder la valeur cible comme attribut pour référence
            element.setAttribute('data-target', target);
            requestAnimationFrame(updateValue);
        });
    }

    // ============================================
    // 2. NOTIFICATIONS TOAST
    // ============================================
    function initNotifications() {
        // Vérifier s'il y a des alertes à afficher (ex: prêts en retard)
        const alerts = document.querySelectorAll('.alert-data');
        if (alerts.length > 0) {
            alerts.forEach(alert => {
                const message = alert.getAttribute('data-message');
                const type = alert.getAttribute('data-type') || 'info';
                showToast(message, type);
            });
        }
    }

    function showToast(message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            // Créer le conteneur s'il n'existe pas
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${getIconForType(type)}"></i>
            </div>
            <div class="toast-body">
                <p>${message}</p>
            </div>
            <button class="toast-close">&times;</button>
        `;

        document.querySelector('.toast-container').appendChild(toast);

        // Auto-fermeture après 5 secondes
        setTimeout(() => {
            toast.remove();
        }, 5000);

        // Bouton de fermeture
        toast.querySelector('.toast-close').addEventListener('click', function() {
            toast.remove();
        });
    }

    function getIconForType(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    // ============================================
    // 3. RAFRAÎCHISSEMENT AUTOMATIQUE DES STATS (AJAX)
    // ============================================
    function initAutoRefresh() {
        const refreshInterval = parseInt(document.querySelector('.refresh-interval')?.getAttribute('data-interval') || 30000);
        const statsContainer = document.querySelector('.stats-container');
        
        if (!statsContainer) return;

        let refreshTimer = null;

        function refreshStats() {
            fetch('/api/stat/dashboard')
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour les KPI
                    document.querySelectorAll('.kpi-value').forEach(el => {
                        const key = el.getAttribute('data-key');
                        if (data[key] !== undefined) {
                            el.textContent = data[key];
                        }
                    });
                })
                .catch(error => {
                    console.warn('Erreur lors du rafraîchissement des stats:', error);
                });
        }

        // Démarrer le rafraîchissement
        if (refreshInterval > 0) {
            refreshTimer = setInterval(refreshStats, refreshInterval);
        }

        // Nettoyer le timer si la page est quittée
        window.addEventListener('beforeunload', function() {
            if (refreshTimer) {
                clearInterval(refreshTimer);
            }
        });
    }

    // ============================================
    // 4. GESTION DU TOGGLE SIDEBAR (si pas déjà fait)
    // ============================================
    function initSidebarToggle() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');

        if (toggleBtn && sidebar) {
            // État du sidebar (pour le stockage en localStorage)
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed' && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
            }

            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
            });

            // Fermer sur les écrans mobiles
            if (window.innerWidth <= 768) {
                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('open');
                    }
                });
            }
        }
    }

    // ============================================
    // 5. GRAPHIQUES : Mise à jour dynamique
    // ============================================
    function initChartUpdates() {
        // Si des graphiques existent, on peut les mettre à jour avec les données de l'API
        // Déjà géré par Chart.js dans dashboard.php
    }

    // ============================================
    // 6. TOOLTIPS ET INFO-BULLES
    // ============================================
    function initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(el => {
            el.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-custom';
                tooltip.textContent = this.getAttribute('data-tooltip');
                document.body.appendChild(tooltip);
                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
                tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2) + 'px';
                this._tooltip = tooltip;
            });
            el.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    delete this._tooltip;
                }
            });
        });
    }

    // ============================================
    // INITIALISATION
    // ============================================
    
    // Lancer l'animation des chiffres après un court délai
    setTimeout(animateNumbers, 300);

    // Initialiser les notifications
    initNotifications();

    // Initialiser le toggle sidebar
    initSidebarToggle();

    // Initialiser les tooltips
    initTooltips();

    // Activer le rafraîchissement auto (si vous avez une API)
    // initAutoRefresh(); // Décommentez si vous avez une API /api/stat/dashboard

    console.log('✅ Dashboard initialisé avec succès');
});