<?php
require __DIR__ . '/header.php';
$adminPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="/admin" class="<?= $adminPath === '/admin' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</a>
        <a href="/admin/utilisateurs" class="<?= $adminPath === '/admin/utilisateurs' ? 'active' : '' ?>"><i class="bi bi-people-fill me-2"></i>Utilisateurs</a>
        <a href="/admin/rapports" class="<?= $adminPath === '/admin/rapports' ? 'active' : '' ?>"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i>Rapports</a>
        <a href="/admin/categories" class="<?= $adminPath === '/admin/categories' ? 'active' : '' ?>"><i class="bi bi-tags-fill me-2"></i>Categories</a>
        <a href="/admin/journal" class="<?= $adminPath === '/admin/journal' ? 'active' : '' ?>"><i class="bi bi-journal-text me-2"></i>Journal d'activite</a>
    </aside>
    <div class="admin-content">
