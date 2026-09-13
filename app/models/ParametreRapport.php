<?php

namespace App\Models;

class ParametreRapport
{
    public int $id_parametre;
    public int $id_rapport;
    public string $nom_param_obi;
    public string $libelle_affiche;
    public string $type;
    public bool $obligatoire;
    public ?string $valeurs_liste;

    public static function fromRow(array $row): self
    {
        $p = new self();
        $p->id_parametre     = (int) $row['ID_PARAMETRE'];
        $p->id_rapport       = (int) $row['ID_RAPPORT'];
        $p->nom_param_obi    = $row['NOM_PARAM_OBI'];
        $p->libelle_affiche  = $row['LIBELLE_AFFICHE'];
        $p->type             = $row['TYPE'];
        $p->obligatoire      = ((int) $row['OBLIGATOIRE']) === 1;
        $p->valeurs_liste    = $row['VALEURS_LISTE'] ?? null;
        return $p;
    }

    public function listeValeurs(): array
    {
        if (empty($this->valeurs_liste)) {
            return [];
        }
        return array_map('trim', explode(',', $this->valeurs_liste));
    }
}
