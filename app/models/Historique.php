<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Historique
{
    public static function log(int $idUtilisateur, int $idRapport, string $action, string $format, int $dureeMs): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO HISTORIQUE_CONSULTATION (ID_UTILISATEUR, ID_RAPPORT, ACTION, FORMAT_EXPORT, DUREE_MS)
             VALUES (:id_utilisateur, :id_rapport, :action, :format, :duree)"
        );
        $stmt->execute([
            'id_utilisateur' => $idUtilisateur,
            'id_rapport'     => $idRapport,
            'action'         => $action,
            'format'         => $format,
            'duree'          => $dureeMs,
        ]);
    }
}
