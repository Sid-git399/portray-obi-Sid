<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Categorie
{
    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM CATEGORIE ORDER BY NOM_CATEGORIE")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $nom, string $description): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO CATEGORIE (NOM_CATEGORIE, DESCRIPTION) VALUES (:nom, :description)");
        $stmt->execute(['nom' => $nom, 'description' => $description]);
    }

    public static function delete(int $id): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM CATEGORIE WHERE ID_CATEGORIE = :id");
        $stmt->execute(['id' => $id]);
    }
}
