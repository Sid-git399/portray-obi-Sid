<?php

namespace App\Controllers;

use App\Core\{Auth, Database, Csrf};
use App\Models\{Utilisateur, Rapport, Categorie};
use PDO;

class AdminController
{
    public function dashboard(): void
    {
        Auth::requireRole(['ADMIN']);
        $db = Database::getConnection();

        $nbUtilisateurs = (int) $db->query("SELECT COUNT(*) AS C FROM UTILISATEUR")->fetch(PDO::FETCH_ASSOC)['C'];
        $nbRapports     = (int) $db->query("SELECT COUNT(*) AS C FROM RAPPORT WHERE STATUT = 'ACTIF'")->fetch(PDO::FETCH_ASSOC)['C'];
        $nbTelechargements = (int) $db->query("SELECT COUNT(*) AS C FROM HISTORIQUE_CONSULTATION WHERE ACTION = 'TELECHARGEMENT'")->fetch(PDO::FETCH_ASSOC)['C'];
        $dureeMoyenne = (float) ($db->query("SELECT NVL(AVG(DUREE_MS),0) AS M FROM HISTORIQUE_CONSULTATION")->fetch(PDO::FETCH_ASSOC)['M'] ?? 0);

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // ===================== UTILISATEURS =====================

    public function utilisateurs(): void
    {
        Auth::requireRole(['ADMIN']);
        $utilisateurs = Utilisateur::all();
        require __DIR__ . '/../views/admin/utilisateurs.php';
    }

    public function creerUtilisateur(): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $nom    = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';
        $role   = $_POST['role'] ?? 'UTILISATEUR';

        if ($nom === '' || $prenom === '' || $email === '' || strlen($motDePasse) < 8) {
            $_SESSION['flash_erreur'] = "Tous les champs sont obligatoires (mot de passe : 8 caracteres minimum).";
            header('Location: /admin/utilisateurs');
            exit;
        }

        if (!in_array($role, ['ADMIN', 'UTILISATEUR'], true)) {
            $role = 'UTILISATEUR';
        }

        try {
            Utilisateur::create($nom, $prenom, $email, $motDePasse, $role);
            Auth::logAction("Creation utilisateur : {$email}");
            $_SESSION['flash_succes'] = "Utilisateur cree avec succes.";
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash_erreur'] = "Impossible de creer l'utilisateur (email deja utilise ?).";
        }

        header('Location: /admin/utilisateurs');
        exit;
    }

    public function toggleUtilisateur(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        Utilisateur::toggleActif((int) $id);
        Auth::logAction("Activation/desactivation utilisateur #{$id}");

        header('Location: /admin/utilisateurs');
        exit;
    }

    public function modifierUtilisateur(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $role = $_POST['role'] ?? 'UTILISATEUR';
        if (!in_array($role, ['ADMIN', 'UTILISATEUR'], true)) {
            $role = 'UTILISATEUR';
        }

        Utilisateur::updateRole((int) $id, $role);
        Auth::logAction("Modification role utilisateur #{$id} -> {$role}");

        header('Location: /admin/utilisateurs');
        exit;
    }

    // ===================== RAPPORTS =====================

    public function rapports(): void
    {
        Auth::requireRole(['ADMIN']);
        $rapports   = Rapport::allAdmin();
        $categories = Categorie::all();
        require __DIR__ . '/../views/admin/rapports.php';
    }

    public function creerRapport(): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $data = $this->extraireDonneesRapport();

        if ($data['titre'] === '' || $data['chemin_catalogue_obi'] === '') {
            $_SESSION['flash_erreur'] = "Le titre et le chemin du catalogue OBI sont obligatoires.";
            header('Location: /admin/rapports');
            exit;
        }

        Rapport::create($data);
        Auth::logAction("Creation rapport : {$data['titre']}");
        $_SESSION['flash_succes'] = "Rapport cree avec succes.";

        header('Location: /admin/rapports');
        exit;
    }

    public function modifierRapport(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $data = $this->extraireDonneesRapport();
        Rapport::update((int) $id, $data);
        Auth::logAction("Modification rapport #{$id}");
        $_SESSION['flash_succes'] = "Rapport modifie avec succes.";

        header('Location: /admin/rapports');
        exit;
    }

    public function supprimerRapport(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        Rapport::delete((int) $id);
        Auth::logAction("Suppression rapport #{$id}");
        $_SESSION['flash_succes'] = "Rapport supprime.";

        header('Location: /admin/rapports');
        exit;
    }

    public function ajouterParametre(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $data = [
            'nom_param_obi'   => trim($_POST['nom_param_obi'] ?? ''),
            'libelle_affiche' => trim($_POST['libelle_affiche'] ?? ''),
            'type'            => $_POST['type'] ?? 'TEXTE',
            'obligatoire'     => !empty($_POST['obligatoire']),
            'valeurs_liste'   => trim($_POST['valeurs_liste'] ?? ''),
        ];

        if ($data['nom_param_obi'] !== '' && $data['libelle_affiche'] !== '') {
            Rapport::ajouterParametre((int) $id, $data);
            $_SESSION['flash_succes'] = "Parametre ajoute.";
        }

        header('Location: /admin/rapports');
        exit;
    }

    public function supprimerParametre(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        Rapport::supprimerParametre((int) $id);
        header('Location: /admin/rapports');
        exit;
    }

    private function extraireDonneesRapport(): array
    {
        return [
            'titre'                => trim($_POST['titre'] ?? ''),
            'description'          => trim($_POST['description'] ?? ''),
            'id_categorie'         => !empty($_POST['id_categorie']) ? (int) $_POST['id_categorie'] : null,
            'chemin_catalogue_obi' => trim($_POST['chemin_catalogue_obi'] ?? ''),
            'template_obi'         => trim($_POST['template_obi'] ?? ''),
            'formats_dispo'        => trim($_POST['formats_dispo'] ?? 'pdf,excel2007,rtf'),
        ];
    }

    // ===================== CATEGORIES =====================

    public function categories(): void
    {
        Auth::requireRole(['ADMIN']);
        $categories = Categorie::all();
        require __DIR__ . '/../views/admin/categories.php';
    }

    public function creerCategorie(): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        $nom = trim($_POST['nom_categorie'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($nom !== '') {
            Categorie::create($nom, $description);
            $_SESSION['flash_succes'] = "Categorie creee.";
        }

        header('Location: /admin/categories');
        exit;
    }

    public function supprimerCategorie(string $id): void
    {
        Auth::requireRole(['ADMIN']);
        Csrf::verify();

        Categorie::delete((int) $id);
        header('Location: /admin/categories');
        exit;
    }

    // ===================== JOURNAL =====================

    public function journal(): void
    {
        Auth::requireRole(['ADMIN']);
        $db = Database::getConnection();
        $journal = $db->query(
            "SELECT j.*, u.NOM, u.PRENOM FROM JOURNAL_ACTIVITE j
             JOIN UTILISATEUR u ON j.ID_UTILISATEUR = u.ID_UTILISATEUR
             ORDER BY DATE_ACTION DESC FETCH FIRST 100 ROWS ONLY"
        )->fetchAll(PDO::FETCH_ASSOC);

        $historique = $db->query(
            "SELECT h.*, u.NOM, u.PRENOM, r.TITRE FROM HISTORIQUE_CONSULTATION h
             JOIN UTILISATEUR u ON h.ID_UTILISATEUR = u.ID_UTILISATEUR
             JOIN RAPPORT r ON h.ID_RAPPORT = r.ID_RAPPORT
             ORDER BY DATE_CONSULTATION DESC FETCH FIRST 100 ROWS ONLY"
        )->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/admin/journal.php';
    }
}
