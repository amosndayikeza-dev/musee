<div class="container">
    <h1><i class="fas fa-project-diagram"></i> Diagrammes UML</h1>

    <!-- Diagramme de cas d'utilisation -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>📊 Diagramme de cas d'utilisation</h2>
        <p>Acteurs : Administrateur, Conservateur, Visiteur</p>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #e9ecef;">
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/musee/musee_national/public/images/documentation/diagramme_cas_utilisation.png')): ?>
                <img src="<?= BASE_URL ?>images/documentation/diagramme_cas_utilisation.png" alt="Diagramme de cas d'utilisation" style="max-width:100%; height:auto; border-radius:8px;">
            <?php else: ?>
                <p style="color: #999;">
                    <i class="fas fa-upload" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                    Diagramme de cas d'utilisation à insérer<br>
                    <small>Placez l'image dans : public/images/documentation/diagramme_cas_utilisation.png</small>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Diagramme de classes -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>📊 Diagramme de classes</h2>
        <p>Structure MVC : Controller, Model, Service, View</p>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #e9ecef;">
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/musee/musee_national/public/images/documentation/diagramme_classes.png')): ?>
                <img src="<?= BASE_URL ?>images/documentation/diagramme_classes.png" alt="Diagramme de classes" style="max-width:100%; height:auto; border-radius:8px;">
            <?php else: ?>
                <p style="color: #999;">
                    <i class="fas fa-upload" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                    Diagramme de classes à insérer<br>
                    <small>Placez l'image dans : public/images/documentation/diagramme_classes.png</small>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Diagramme d'activité -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <h2>📊 Diagramme d'activité</h2>
        <p>Flux de travail : Gestion des prêts</p>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #e9ecef;">
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/musee/musee_national/public/images/documentation/diagramme_activite.png')): ?>
                <img src="<?= BASE_URL ?>images/documentation/diagramme_activite.png" alt="Diagramme d'activité" style="max-width:100%; height:auto; border-radius:8px;">
            <?php else: ?>
                <p style="color: #999;">
                    <i class="fas fa-upload" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                    Diagramme d'activité à insérer<br>
                    <small>Placez l'image dans : public/images/documentation/diagramme_activite.png</small>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>