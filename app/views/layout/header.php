<?php
use App\Core\Auth;
$user = Auth::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail OBI &mdash; Consultation des rapports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-obi sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="logo-badge">OBI</span>
            Portail Reporting
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navObi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navObi">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/catalogue' ? 'active' : '' ?>" href="/catalogue">
                            <i class="bi bi-grid-fill me-1"></i>Catalogue
                        </a>
                    </li>
                    <?php if ($user['role'] === 'ADMIN'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/admin') ? 'active' : '' ?>" href="/admin">
                                <i class="bi bi-gear-fill me-1"></i>Administration
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['nom']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-connexion btn-sm" href="/logout">Deconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-connexion btn-sm px-3" href="/login">Se connecter</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (!empty($_SESSION['flash_succes'])): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show alert-auto-dismiss" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_succes']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_succes']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_erreur'])): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show alert-auto-dismiss" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_erreur']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_erreur']); ?>
<?php endif; ?>

<div id="loading-overlay" class="loading-overlay">
    <div class="spinner-obi"></div>
    <div>Generation du rapport en cours via Oracle BI Publisher&hellip;</div>
</div>
