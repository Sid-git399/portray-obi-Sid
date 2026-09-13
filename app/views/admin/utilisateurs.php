<?php
use App\Core\Csrf;
require __DIR__ . '/../layout/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color: var(--obi-navy);">Gestion des utilisateurs</h2>
    <button class="btn btn-obi-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelUtilisateur">
        <i class="bi bi-plus-lg me-1"></i>Nouvel utilisateur
    </button>
</div>

<div class="card card-obi">
    <div class="table-responsive">
        <table class="table table-obi align-middle mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Statut</th>
                    <th>Cree le</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['PRENOM'] . ' ' . $u['NOM']) ?></td>
                        <td><?= htmlspecialchars($u['EMAIL']) ?></td>
                        <td>
                            <form method="post" action="/admin/utilisateurs/<?= $u['ID_UTILISATEUR'] ?>/modifier" class="d-flex gap-2 align-items-center">
                                <?= Csrf::field() ?>
                                <select name="role" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                                    <option value="UTILISATEUR" <?= str_contains($u['ROLES'] ?? '', 'UTILISATEUR') ? 'selected' : '' ?>>UTILISATEUR</option>
                                    <option value="ADMIN" <?= str_contains($u['ROLES'] ?? '', 'ADMIN') ? 'selected' : '' ?>>ADMIN</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php if ((int) $u['ACTIF'] === 1): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Desactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?= htmlspecialchars($u['DATE_CREATION'] ?? '') ?></td>
                        <td class="text-end">
                            <form method="post" action="/admin/utilisateurs/<?= $u['ID_UTILISATEUR'] ?>/toggle" class="d-inline">
                                <?= Csrf::field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <?= (int) $u['ACTIF'] === 1 ? 'Desactiver' : 'Activer' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal creation utilisateur -->
<div class="modal fade" id="modalNouvelUtilisateur" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/utilisateurs/creer">
                <?= Csrf::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Nouvel utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Prenom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="UTILISATEUR">UTILISATEUR</option>
                                <option value="ADMIN">ADMIN</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-obi-primary">Creer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
