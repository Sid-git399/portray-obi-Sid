<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Utilisateur
{
    public static function findByEmail(string $email): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT u.ID_UTILISATEUR, u.NOM, u.PRENOM, u.EMAIL, u.MOT_DE_PASSE, u.ACTIF, r.NOM_ROLE
             FROM UTILISATEUR u
             JOIN UTILISATEUR_ROLE ur ON u.ID_UTILISATEUR = ur.ID_UTILISATEUR
             JOIN ROLE r ON ur.ID_ROLE = r.ID_ROLE
             WHERE u.EMAIL = :email"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function all(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT u.ID_UTILISATEUR, u.NOM, u.PRENOM, u.EMAIL, u.ACTIF, u.DATE_CREATION,
                    LISTAGG(r.NOM_ROLE, ', ') WITHIN GROUP (ORDER BY r.NOM_ROLE) AS ROLES
             FROM UTILISATEUR u
             LEFT JOIN UTILISATEUR_ROLE ur ON u.ID_UTILISATEUR = ur.ID_UTILISATEUR
             LEFT JOIN ROLE r ON ur.ID_ROLE = r.ID_ROLE
             GROUP BY u.ID_UTILISATEUR, u.NOM, u.PRENOM, u.EMAIL, u.ACTIF, u.DATE_CREATION
             ORDER BY u.NOM"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $nom, string $prenom, string $email, string $motDePasse, string $role): int
    {
        $db = Database::getConnection();
        $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

        $stmt = $db->prepare(
            "INSERT INTO UTILISATEUR (NOM, PRENOM, EMAIL, MOT_DE_PASSE) VALUES (:nom, :prenom, :email, :hash)"
        );
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'hash' => $hash]);

        $id = (int) $db->lastInsertId();

        $stmtRole = $db->prepare(
            "INSERT INTO UTILISATEUR_ROLE (ID_UTILISATEUR, ID_ROLE)
             SELECT :id, ID_ROLE FROM ROLE WHERE NOM_ROLE = :role"
        );
        $stmtRole->execute(['id' => $id, 'role' => $role]);

        return $id;
    }

    public static function toggleActif(int $id): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE UTILISATEUR SET ACTIF = 1 - ACTIF WHERE ID_UTILISATEUR = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function updateRole(int $id, string $role): void
    {
        $db = Database::getConnection();
        $db->prepare("DELETE FROM UTILISATEUR_ROLE WHERE ID_UTILISATEUR = :id")->execute(['id' => $id]);
        $stmt = $db->prepare(
            "INSERT INTO UTILISATEUR_ROLE (ID_UTILISATEUR, ID_ROLE)
             SELECT :id, ID_ROLE FROM ROLE WHERE NOM_ROLE = :role"
        );
        $stmt->execute(['id' => $id, 'role' => $role]);
    }
}
