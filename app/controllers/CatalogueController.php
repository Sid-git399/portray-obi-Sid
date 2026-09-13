<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Rapport;
use App\Models\Categorie;

class CatalogueController
{
    public function index(): void
    {
        Auth::requireLogin();

        $idCategorie = isset($_GET['categorie']) && $_GET['categorie'] !== '' ? (int) $_GET['categorie'] : null;
        $recherche   = trim($_GET['q'] ?? '');

        $rapports   = Rapport::all($idCategorie, $recherche !== '' ? $recherche : null);
        $categories = Categorie::all();

        require __DIR__ . '/../views/catalogue/index.php';
    }
}
