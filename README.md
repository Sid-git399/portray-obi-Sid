# Portail Web PHP — Consultation et Generation de Rapports Oracle BI (OBIEE 11g)

Portail web PHP permettant a des utilisateurs metier de consulter un catalogue de rapports,
d'appliquer des filtres simples, et de declencher en arriere-plan un appel Web Service SOAP
vers Oracle BI Publisher pour generer et telecharger le rapport (PDF, Excel, Word).

## Prerequis serveur

```bash
php -v          # PHP >= 8.0
php -m | grep -i soap   # doit afficher "soap"
php -m | grep -i oci    # doit afficher "oci8" (ou pdo_oci)
```

Si `soap` n'apparait pas dans `php -m`, decommenter `extension=soap` dans `php.ini` et redemarrer PHP-FPM/Apache.

## Installation

```bash
cd portail-obi
composer install
cp .env.example .env
# editer .env avec vos identifiants Oracle et OBI
```

## Base de donnees

Executer dans l'ordre, sur votre base Oracle applicative (celle du portail, distincte de l'entrepot OBI) :

```bash
sqlplus portail_user/motdepasse@XE @database/schema.sql
sqlplus portail_user/motdepasse@XE @database/seed.sql
```

Comptes de demo crees par `seed.sql` (mot de passe : `password123`) :

| Email | Role |
|---|---|
| admin@portail.local | ADMIN |
| ny_kahlouche@esi.dz | UTILISATEUR |

## Mode OBI : live vs demo

Dans `.env`, la variable `OBI_MODE` controle le comportement du client OBI :

- `OBI_MODE=live` : appelle reellement le Web Service SOAP de BI Publisher (`OBI_WSDL`, `OBI_USERNAME`, `OBI_PASSWORD`).
- `OBI_MODE=demo` : ne contacte pas OBI ; sert des fichiers pre-generes places dans `storage/demo_reports/`
  (filet de securite en cas de serveur OBI injoignable — voir section 12 du guide de conception).

## Test de connexion OBI isole

Avant de lancer l'application, valider la connectivite avec :

```bash
php test_obi.php
```

Ce script est a supprimer avant la soutenance.

## Lancer le serveur de developpement

```bash
php -S localhost:8000 -t public
```

Puis ouvrir http://localhost:8000

## Arborescence

```
portail-obi/
├── config/            # database.php, obi.php, app.php
├── public/            # front controller + assets (css/js)
├── app/
│   ├── controllers/
│   ├── models/
│   ├── services/      # ObiPublisherClient.php (SOAP)
│   ├── views/
│   ├── core/           # Router, Database, Auth, Csrf
│   └── middlewares/
├── database/          # schema.sql, seed.sql
├── storage/demo_reports/  # fichiers de secours pour OBI_MODE=demo
└── test_obi.php       # script de verification SOAP
```

## Securite

- Requetes preparees partout (PDO_OCI)
- Mots de passe hashes bcrypt (`password_hash` / `password_verify`)
- Sessions regénérées a la connexion, cookies HttpOnly + SameSite=Lax
- Protection CSRF sur tous les formulaires POST (`App\Core\Csrf`)
- Identifiants OBI en variables d'environnement, jamais exposes cote client
- Toute erreur SOAP/SQL loguee cote serveur, jamais affichee brute a l'utilisateur
