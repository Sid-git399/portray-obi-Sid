<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Rapport
{
    public int $id_rapport;
    public string $titre;
    public ?string $description;
    public ?int $id_categorie;
    public ?string $nom_categorie = null;
    public string $chemin_catalogue_obi;
    public ?string $template_obi;
    public string $formats_dispo;
    public string $statut;

    private array $parametres;

    public static function fromRow(array $row): self
    {
        $r = new self();
        $r->id_rapport            = (int) $row['ID_RAPPORT'];
        $r->titre                 = $row['TITRE'];
        $r->description           = $row['DESCRIPTION'] ?? null;
        $r->id_categorie          = isset($row['ID_CATEGORIE']) ? (int) $row['ID_CATEGORIE'] : null;
        $r->nom_categorie         = $row['NOM_CATEGORIE'] ?? null;
        $r->chemin_catalogue_obi  = $row['CHEMIN_CATALOGUE_OBI'];
        $r->template_obi          = $row['TEMPLATE_OBI'] ?? null;
        $r->formats_dispo         = $row['FORMATS_DISPO'] ?? 'pdf';
        $r->statut                = $row['STATUT'] ?? 'ACTIF';
        return $r;
    }

    public function formats(): array
    {
        return array_map('trim', explode(',', $this->formats_dispo));
    }

    public function getParametres(): array
    {
        if (!isset($this->parametres)) {
            $this->parametres = self::chargerParametres($this->id_rapport);
        }
        return $this->parametres;
    }

    public static function chargerParametres(int $idRapport): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM PARAMETRE_RAPPORT WHERE ID_RAPPORT = :id ORDER BY ID_PARAMETRE");
        $stmt->execute(['id' => $idRapport]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([ParametreRapport::class, 'fromRow'], $rows);
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT r.*, c.NOM_CATEGORIE
             FROM RAPPORT r
             LEFT JOIN CATEGORIE c ON r.ID_CATEGORIE = c.ID_CATEGORIE
             WHERE r.ID_RAPPORT = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromRow($row) : null;
    }

    public static function all(?int $idCategorie = null, ?string $recherche = null): array
    {
        $db = Database::getConnection();
        $sql = "SELECT r.*, c.NOM_CATEGORIE
                FROM RAPPORT r
                LEFT JOIN CATEGORIE c ON r.ID_CATEGORIE = c.ID_CATEGORIE
                WHERE r.STATUT = 'ACTIF'";
        $params = [];

        if ($idCategorie) {
            $sql .= " AND r.ID_CATEGORIE = :cat";
            $params['cat'] = $idCategorie;
        }
        if ($recherche) {
            $sql .= " AND UPPER(r.TITRE) LIKE UPPER(:q)";
            $params['q'] = '%' . $recherche . '%';
        }
        $sql .= " ORDER BY r.TITRE";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([self::class, 'fromRow'], $rows);
    }

    public static function allAdmin(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT r.*, c.NOM_CATEGORIE FROM RAPPORT r
             LEFT JOIN CATEGORIE c ON r.ID_CATEGORIE = c.ID_CATEGORIE
             ORDER BY r.TITRE"
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([self::class, 'fromRow'], $rows);
    }

    public static function create(array $data): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO RAPPORT (TITRE, DESCRIPTION, ID_CATEGORIE, CHEMIN_CATALOGUE_OBI, TEMPLATE_OBI, FORMATS_DISPO)
             VALUES (:titre, :description, :id_categorie, :chemin, :template, :formats)"
        );
        $stmt->execute([
            'titre'        => $data['titre'],
            'description'  => $data['description'],
            'id_categorie' => $data['id_categorie'],
            'chemin'       => $data['chemin_catalogue_obi'],
            'template'     => $data['template_obi'] ?: null,
            'formats'      => $data['formats_dispo'],
        ]);
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "UPDATE RAPPORT SET TITRE = :titre, DESCRIPTION = :description, ID_CATEGORIE = :id_categorie,
                CHEMIN_CATALOGUE_OBI = :chemin, TEMPLATE_OBI = :template, FORMATS_DISPO = :formats
             WHERE ID_RAPPORT = :id"
        );
        $stmt->execute([
            'titre'        => $data['titre'],
            'description'  => $data['description'],
            'id_categorie' => $data['id_categorie'],
            'chemin'       => $data['chemin_catalogue_obi'],
            'template'     => $data['template_obi'] ?: null,
            'formats'      => $data['formats_dispo'],
            'id'           => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $db = Database::getConnection();
        $db->prepare("DELETE FROM PARAMETRE_RAPPORT WHERE ID_RAPPORT = :id")->execute(['id' => $id]);
        $db->prepare("DELETE FROM RAPPORT WHERE ID_RAPPORT = :id")->execute(['id' => $id]);
    }

    public static function ajouterParametre(int $idRapport, array $data): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO PARAMETRE_RAPPORT (ID_RAPPORT, NOM_PARAM_OBI, LIBELLE_AFFICHE, TYPE, OBLIGATOIRE, VALEURS_LISTE)
             VALUES (:id_rapport, :nom, :libelle, :type, :obligatoire, :valeurs)"
        );
        $stmt->execute([
            'id_rapport'  => $idRapport,
            'nom'         => $data['nom_param_obi'],
            'libelle'     => $data['libelle_affiche'],
            'type'        => $data['type'],
            'obligatoire' => !empty($data['obligatoire']) ? 1 : 0,
            'valeurs'     => $data['valeurs_liste'] ?? null,
        ]);
    }

    public static function supprimerParametre(int $idParametre): void
    {
        $db = Database::getConnection();
        $db->prepare("DELETE FROM PARAMETRE_RAPPORT WHERE ID_PARAMETRE = :id")->execute(['id' => $idParametre]);
    }
}
