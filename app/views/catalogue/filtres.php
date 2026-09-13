<?php
use App\Core\Csrf;
require __DIR__ . '/../layout/header.php';
$parametres = $rapport->getParametres();
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/catalogue">Catalogue</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($rapport->titre) ?></li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-obi">
                <div class="card-body p-4">
                    <?php if ($rapport->nom_categorie): ?>
                        <span class="badge-categorie mb-2 d-inline-block"><?= htmlspecialchars($rapport->nom_categorie) ?></span>
                    <?php endif; ?>
                    <h3 class="fw-bold" style="color: var(--obi-navy);"><?= htmlspecialchars($rapport->titre) ?></h3>
                    <p class="text-muted"><?= htmlspecialchars($rapport->description ?? '') ?></p>

                    <hr class="my-4">

                    <form method="post" action="/rapport/<?= $rapport->id_rapport ?>/export" id="form-export-rapport">
                        <?= Csrf::field() ?>

                        <?php if (!empty($parametres)): ?>
                            <h6 class="fw-semibold text-uppercase small text-muted mb-3">Filtres</h6>
                            <div class="row g-3 mb-4">
                                <?php foreach ($parametres as $param): ?>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <?= htmlspecialchars($param->libelle_affiche) ?>
                                            <?php if ($param->obligatoire): ?><span class="text-danger">*</span><?php endif; ?>
                                        </label>

                                        <?php if ($param->type === 'DATE'): ?>
                                            <input type="date" name="filtre_<?= htmlspecialchars($param->nom_param_obi) ?>"
                                                   class="form-control" <?= $param->obligatoire ? 'required' : '' ?>>
                                        <?php elseif ($param->type === 'LISTE'): ?>
                                            <select name="filtre_<?= htmlspecialchars($param->nom_param_obi) ?>"
                                                    class="form-select" <?= $param->obligatoire ? 'required' : '' ?>>
                                                <option value="">-- Selectionner --</option>
                                                <?php foreach ($param->listeValeurs() as $valeur): ?>
                                                    <option value="<?= htmlspecialchars($valeur) ?>"><?= htmlspecialchars($valeur) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php elseif ($param->type === 'NOMBRE'): ?>
                                            <input type="number" name="filtre_<?= htmlspecialchars($param->nom_param_obi) ?>"
                                                   class="form-control" <?= $param->obligatoire ? 'required' : '' ?>>
                                        <?php else: ?>
                                            <input type="text" name="filtre_<?= htmlspecialchars($param->nom_param_obi) ?>"
                                                   class="form-control" <?= $param->obligatoire ? 'required' : '' ?>>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Ce rapport ne necessite aucun filtre.</p>
                        <?php endif; ?>

                        <h6 class="fw-semibold text-uppercase small text-muted mb-3">Format d'export</h6>
                        <div class="d-flex gap-3 mb-4 flex-wrap">
                            <?php foreach ($rapport->formats() as $i => $format): ?>
                                <?php
                                    $labels = ['pdf' => 'PDF', 'excel2007' => 'Excel', 'xls' => 'Excel', 'rtf' => 'Word', 'csv' => 'CSV', 'html' => 'HTML'];
                                    $icons  = ['pdf' => 'bi-filetype-pdf', 'excel2007' => 'bi-filetype-xlsx', 'xls' => 'bi-filetype-xlsx', 'rtf' => 'bi-filetype-doc', 'csv' => 'bi-filetype-csv', 'html' => 'bi-filetype-html'];
                                ?>
                                <label class="form-check-label d-flex align-items-center gap-2 border rounded-3 px-3 py-2" style="cursor:pointer;">
                                    <input type="radio" name="format" value="<?= htmlspecialchars($format) ?>" class="form-check-input mt-0" <?= $i === 0 ? 'checked' : '' ?> required>
                                    <i class="bi <?= $icons[$format] ?? 'bi-file-earmark' ?>"></i>
                                    <?= $labels[$format] ?? htmlspecialchars($format) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-obi-primary py-2 px-4">
                            <i class="bi bi-download me-2"></i>Telecharger le rapport
                        </button>
                        <a href="/catalogue" class="btn btn-outline-secondary py-2 px-4">Retour</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
