<?php
use App\Core\Csrf;
require __DIR__ . '/../layout/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height:75vh">
    <div class="card card-login" style="max-width:420px; width:100%">
        <div class="card-header">
            <div class="logo-badge">OBI</div>
            <h4 class="mb-0">Connexion au portail</h4>
            <div class="small mt-1" style="opacity:.85">Service des Traitements Centraux</div>
        </div>
        <div class="card-body p-4">
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <form method="post" action="/login">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required autofocus placeholder="prenom.nom@banque.dz">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>
                <button type="submit" class="btn btn-obi-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>
            </form>
            <p class="text-muted small text-center mt-3 mb-0">
                Pas de compte ? Contactez votre administrateur du portail.
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
