<?php require __DIR__ . '/../layout/admin_header.php'; ?>

<h2 class="fw-bold mb-4" style="color: var(--obi-navy);">Journal d'activite</h2>

<ul class="nav nav-tabs mb-3" id="journalTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-connexions" type="button">Connexions &amp; actions admin</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-telechargements" type="button">Telechargements de rapports</button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-connexions">
        <div class="card card-obi">
            <div class="table-responsive">
                <table class="table table-obi align-middle mb-0">
                    <thead>
                        <tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Adresse IP</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($journal as $j): ?>
                            <tr>
                                <td class="small"><?= htmlspecialchars($j['DATE_ACTION'] ?? '') ?></td>
                                <td><?= htmlspecialchars($j['PRENOM'] . ' ' . $j['NOM']) ?></td>
                                <td><?= htmlspecialchars($j['ACTION'] ?? '') ?></td>
                                <td class="small text-muted"><?= htmlspecialchars($j['ADRESSE_IP'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-telechargements">
        <div class="card card-obi">
            <div class="table-responsive">
                <table class="table table-obi align-middle mb-0">
                    <thead>
                        <tr><th>Date</th><th>Utilisateur</th><th>Rapport</th><th>Format</th><th>Duree OBI (ms)</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $h): ?>
                            <tr>
                                <td class="small"><?= htmlspecialchars($h['DATE_CONSULTATION'] ?? '') ?></td>
                                <td><?= htmlspecialchars($h['PRENOM'] . ' ' . $h['NOM']) ?></td>
                                <td><?= htmlspecialchars($h['TITRE'] ?? '') ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars(strtoupper($h['FORMAT_EXPORT'] ?? '')) ?></span></td>
                                <td>
                                    <?php $duree = (int) ($h['DUREE_MS'] ?? 0); ?>
                                    <span class="badge <?= $duree > 5000 ? 'bg-danger' : ($duree > 2000 ? 'bg-warning text-dark' : 'bg-success') ?>">
                                        <?= $duree ?> ms
                                    </span>
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
