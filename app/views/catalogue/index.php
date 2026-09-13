<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5">
    <div class="mb-4">
        <h2 class="fw-bold" style="color: var(--obi-navy);">Catalogue des rapports</h2>
        <p class="text-muted">Selectionnez un rapport pour le filtrer et le telecharger.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="sidebar-filtres mb-4">
                <form method="get" action="/catalogue" class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Recherche</label>
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Titre du rapport..."
                               value="<?= htmlspecialchars($recherche ?? '') ?>">
                        <?php if ($idCategorie): ?>
                            <input type="hidden" name="categorie" value="<?= (int) $idCategorie ?>">
                        <?php endif; ?>
                        <button class="btn btn-obi-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <label class="form-label fw-semibold small text-uppercase text-muted">Categories</label>
                <div class="list-group">
                    <a href="/catalogue<?= $recherche ? '?q=' . urlencode($recherche) : '' ?>"
                       class="list-group-item list-group-item-action <?= !$idCategorie ? 'active' : '' ?>">
                        Toutes les categories
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <?php
                            $params = ['categorie' => $cat['ID_CATEGORIE']];
                            if ($recherche) $params['q'] = $recherche;
                        ?>
                        <a href="/catalogue?<?= http_build_query($params) ?>"
                           class="list-group-item list-group-item-action <?= $idCategorie == $cat['ID_CATEGORIE'] ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['NOM_CATEGORIE']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php if (empty($rapports)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Aucun rapport ne correspond a votre recherche.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($rapports as $rapport): ?>
                        <div class="col-md-6">
                            <div class="card card-obi">
                                <div class="card-body">
                                    <?php if ($rapport->nom_categorie): ?>
                                        <span class="badge-categorie mb-2 align-self-start"><?= htmlspecialchars($rapport->nom_categorie) ?></span>
                                    <?php endif; ?>
                                    <h5 class="fw-bold"><?= htmlspecialchars($rapport->titre) ?></h5>
                                    <p class="text-muted flex-grow-1"><?= htmlspecialchars($rapport->description ?? '') ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="small text-muted">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i><?= htmlspecialchars(strtoupper(str_replace('excel2007', 'xlsx', $rapport->formats_dispo))) ?>
                                        </span>
                                        <a href="/rapport/<?= $rapport->id_rapport ?>" class="btn btn-consulter btn-sm">
                                            Consulter <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
