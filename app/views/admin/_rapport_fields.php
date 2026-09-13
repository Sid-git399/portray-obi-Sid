<?php
/** @var \App\Models\Rapport|null $rapport */
/** @var array $categories */
?>
<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" required value="<?= htmlspecialchars($rapport->titre ?? '') ?>">
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($rapport->description ?? '') ?></textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Categorie</label>
        <select name="id_categorie" class="form-select">
            <option value="">-- Aucune --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['ID_CATEGORIE'] ?>" <?= ($rapport && $rapport->id_categorie == $cat['ID_CATEGORIE']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['NOM_CATEGORIE']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Chemin catalogue OBI</label>
        <input type="text" name="chemin_catalogue_obi" class="form-control" required
               placeholder="/~obi2026/NOM DU RAPPORT.xdo" value="<?= htmlspecialchars($rapport->chemin_catalogue_obi ?? '') ?>">
    </div>
    <div class="col-6">
        <label class="form-label">Template OBI (optionnel)</label>
        <input type="text" name="template_obi" class="form-control" value="<?= htmlspecialchars($rapport->template_obi ?? '') ?>">
    </div>
    <div class="col-6">
        <label class="form-label">Formats disponibles</label>
        <input type="text" name="formats_dispo" class="form-control"
               value="<?= htmlspecialchars($rapport->formats_dispo ?? 'pdf,excel2007,rtf') ?>">
    </div>
</div>
