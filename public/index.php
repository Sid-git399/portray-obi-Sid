<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\{AuthController, HomeController, CatalogueController, RapportController, AdminController};

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!empty($_SERVER['HTTPS'])) {
    ini_set('session.cookie_secure', '1');
}
session_start();

$router = new Router();

// Routes publiques
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Routes protegees
$router->get('/catalogue', [CatalogueController::class, 'index']);
$router->get('/rapport/{id}', [RapportController::class, 'show']);
$router->post('/rapport/{id}/export', [RapportController::class, 'export']);

// Routes admin
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/utilisateurs', [AdminController::class, 'utilisateurs']);
$router->post('/admin/utilisateurs/creer', [AdminController::class, 'creerUtilisateur']);
$router->post('/admin/utilisateurs/{id}/modifier', [AdminController::class, 'modifierUtilisateur']);
$router->post('/admin/utilisateurs/{id}/toggle', [AdminController::class, 'toggleUtilisateur']);

$router->get('/admin/rapports', [AdminController::class, 'rapports']);
$router->post('/admin/rapports/creer', [AdminController::class, 'creerRapport']);
$router->post('/admin/rapports/{id}/modifier', [AdminController::class, 'modifierRapport']);
$router->post('/admin/rapports/{id}/supprimer', [AdminController::class, 'supprimerRapport']);
$router->post('/admin/rapports/{id}/parametres/ajouter', [AdminController::class, 'ajouterParametre']);
$router->post('/admin/parametres/{id}/supprimer', [AdminController::class, 'supprimerParametre']);

$router->get('/admin/categories', [AdminController::class, 'categories']);
$router->post('/admin/categories/creer', [AdminController::class, 'creerCategorie']);
$router->post('/admin/categories/{id}/supprimer', [AdminController::class, 'supprimerCategorie']);

$router->get('/admin/journal', [AdminController::class, 'journal']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
