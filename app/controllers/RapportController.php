<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Services\ObiPublisherClient;
use App\Models\Rapport;
use App\Models\Historique;

class RapportController
{
    public function show(string $id): void
    {
        Auth::requireLogin();

        $rapport = Rapport::findById((int) $id);
        if (!$rapport) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../views/catalogue/filtres.php';
    }

    public function export(string $id): void
    {
        Auth::requireRole(['ADMIN', 'UTILISATEUR']);
        Csrf::verify();

        $idRapport = (int) $id;
        $rapport = Rapport::findById($idRapport);
        if (!$rapport) {
            http_response_code(404);
            exit('Rapport introuvable');
        }

        $format = $_POST['format'] ?? 'pdf';
        if (!in_array($format, $rapport->formats(), true)) {
            http_response_code(400);
            exit('Format non autorise pour ce rapport.');
        }

        $filtres = [];
        foreach ($rapport->getParametres() as $param) {
            $valeur = trim($_POST['filtre_' . $param->nom_param_obi] ?? '');

            if ($param->obligatoire && $valeur === '') {
                http_response_code(400);
                exit("Le filtre « {$param->libelle_affiche} » est obligatoire.");
            }

            if ($valeur !== '') {
                if ($param->type === 'DATE' && !$this->estDateValide($valeur)) {
                    http_response_code(400);
                    exit("Le filtre « {$param->libelle_affiche} » doit etre une date valide (AAAA-MM-JJ).");
                }
                if ($param->type === 'NOMBRE' && !is_numeric($valeur)) {
                    http_response_code(400);
                    exit("Le filtre « {$param->libelle_affiche} » doit etre un nombre.");
                }
                $filtres[$param->nom_param_obi] = $valeur;
            }
        }

        $config = require __DIR__ . '/../../config/obi.php';
        $obi = new ObiPublisherClient($config);

        $debut = microtime(true);
        try {
            $resultat = $obi->runReport($rapport->chemin_catalogue_obi, $format, $filtres);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(502);
            exit("Le service de rapports est momentanement indisponible. Reessayez dans quelques instants.");
        }
        $dureeMs = (int) ((microtime(true) - $debut) * 1000);

        Historique::log((int) $_SESSION['id_utilisateur'], $idRapport, 'TELECHARGEMENT', $format, $dureeMs);

        $extension = match ($format) {
            'pdf' => 'pdf',
            'rtf' => 'rtf',
            'excel2007', 'xls' => 'xlsx',
            'csv' => 'csv',
            default => 'bin',
        };

        $nomFichier = preg_replace('/[^a-zA-Z0-9_-]/', '_', $rapport->titre) . '.' . $extension;

        header('Content-Type: ' . $resultat['contentType']);
        header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
        header('Content-Length: ' . strlen($resultat['content']));
        echo $resultat['content'];
        exit;
    }

    private function estDateValide(string $value): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $value);
        return $d && $d->format('Y-m-d') === $value;
    }
}
