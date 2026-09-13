<?php require __DIR__ . '/../layout/admin_header.php'; ?>

<h2 class="fw-bold mb-4" style="color: var(--obi-navy);">Tableau de bord</h2>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value"><?= $nbUtilisateurs ?></div>
            <div class="stat-label"><i class="bi bi-people-fill me-1"></i>Utilisateurs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value"><?= $nbRapports ?></div>
            <div class="stat-label"><i class="bi bi-file-earmark-bar-graph-fill me-1"></i>Rapports actifs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value"><?= $nbTelechargements ?></div>
            <div class="stat-label"><i class="bi bi-download me-1"></i>Telechargements</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($dureeMoyenne, 0) ?> ms</div>
            <div class="stat-label"><i class="bi bi-speedometer me-1"></i>Temps de reponse OBI moyen</div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle-fill me-2"></i>
    Bienvenue dans l'espace d'administration du portail. Utilisez le menu a gauche pour gerer
    les utilisateurs, le catalogue de rapports, les categories et consulter le journal d'activite.
</div>

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
