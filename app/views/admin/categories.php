<?php
use App\Core\Csrf;
require __DIR__ . '/../layout/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color: var(--obi-navy);">Categories</h2>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-obi">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Ajouter une categorie</h5>
                <form method="post" action="/admin/categories/creer">
                    <?= Csrf::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom_categorie" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-obi-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card card-obi">
            <div class="table-responsive">
                <table class="table table-obi align-middle mb-0">
                    <thead>
                        <tr><th>Nom</th><th>Description</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= htmlspecialchars($cat['NOM_CATEGORIE']) ?></td>
                                <td class="small text-muted"><?= htmlspecialchars($cat['DESCRIPTION'] ?? '') ?></td>
                                <td class="text-end">
                                    <form method="post" action="/admin/categories/<?= $cat['ID_CATEGORIE'] ?>/supprimer" onsubmit="return confirm('Supprimer cette categorie ?')">
                                        <?= Csrf::field() ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
