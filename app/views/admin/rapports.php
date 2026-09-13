<?php
use App\Core\Csrf;
require __DIR__ . '/../layout/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color: var(--obi-navy);">Catalogue de rapports</h2>
    <button class="btn btn-obi-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauRapport">
        <i class="bi bi-plus-lg me-1"></i>Nouveau rapport
    </button>
</div>

<div class="row g-4">
    <?php foreach ($rapports as $rapport): ?>
        <div class="col-lg-6">
            <div class="card card-obi">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <?php if ($rapport->nom_categorie): ?>
                                <span class="badge-categorie mb-2 d-inline-block"><?= htmlspecialchars($rapport->nom_categorie) ?></span>
                            <?php endif; ?>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($rapport->titre) ?></h5>
                        </div>
                        <span class="badge <?= $rapport->statut === 'ACTIF' ? 'bg-success' : 'bg-secondary' ?>"><?= htmlspecialchars($rapport->statut) ?></span>
                    </div>
                    <p class="text-muted small"><?= htmlspecialchars($rapport->description ?? '') ?></p>
                    <p class="small mb-1"><strong>Chemin OBI :</strong> <code><?= htmlspecialchars($rapport->chemin_catalogue_obi) ?></code></p>
                    <p class="small text-muted mb-3"><strong>Formats :</strong> <?= htmlspecialchars($rapport->formats_dispo) ?></p>

                    <details class="mb-3">
                        <summary class="fw-semibold small text-uppercase text-muted" style="cursor:pointer;">Parametres (<?= count($rapport->getParametres()) ?>)</summary>
                        <ul class="list-group list-group-flush mt-2">
                            <?php foreach ($rapport->getParametres() as $param): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">
                                        <strong><?= htmlspecialchars($param->nom_param_obi) ?></strong>
                                        (<?= htmlspecialchars($param->type) ?><?= $param->obligatoire ? ', obligatoire' : '' ?>)
                                        &mdash; <?= htmlspecialchars($param->libelle_affiche) ?>
                                    </span>
                                    <form method="post" action="/admin/parametres/<?= $param->id_parametre ?>/supprimer">
                                        <?= Csrf::field() ?>
                                        <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Supprimer ce parametre ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form method="post" action="/admin/rapports/<?= $rapport->id_rapport ?>/parametres/ajouter" class="row g-2 mt-2">
                            <?= Csrf::field() ?>
                            <div class="col-6"><input type="text" name="nom_param_obi" class="form-control form-control-sm" placeholder="nom_param_obi" required></div>
                            <div class="col-6"><input type="text" name="libelle_affiche" class="form-control form-control-sm" placeholder="Libelle affiche" required></div>
                            <div class="col-5">
                                <select name="type" class="form-select form-select-sm">
                                    <option value="DATE">DATE</option>
                                    <option value="TEXTE">TEXTE</option>
                                    <option value="NOMBRE">NOMBRE</option>
                                    <option value="LISTE">LISTE</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="text" name="valeurs_liste" class="form-control form-control-sm" placeholder="val1,val2 (si LISTE)">
                            </div>
                            <div class="col-3 d-flex align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="obligatoire" value="1" class="form-check-input" id="oblig<?= $rapport->id_rapport ?>">
                                    <label class="form-check-label small" for="oblig<?= $rapport->id_rapport ?>">Obligatoire</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Ajouter le parametre</button>
                            </div>
                        </form>
                    </details>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalModifier<?= $rapport->id_rapport ?>">
                            <i class="bi bi-pencil-square me-1"></i>Modifier
                        </button>
                        <form method="post" action="/admin/rapports/<?= $rapport->id_rapport ?>/supprimer" onsubmit="return confirm('Supprimer ce rapport ?')">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal modification -->
        <div class="modal fade" id="modalModifier<?= $rapport->id_rapport ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="/admin/rapports/<?= $rapport->id_rapport ?>/modifier">
                        <?= Csrf::field() ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier le rapport</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php include __DIR__ . '/_rapport_fields.php'; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-obi-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal creation rapport -->
<div class="modal fade" id="modalNouveauRapport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rapports/creer">
                <?= Csrf::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau rapport</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php $rapport = null; include __DIR__ . '/_rapport_fields.php'; ?>
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
