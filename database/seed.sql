-- =========================================================
-- DONNEES DE DEMO
-- Mot de passe pour tous les comptes ci-dessous : password123
-- Hash bcrypt verifie (compatible password_verify() de PHP).
-- Pour regenerer : php -r "echo password_hash('password123', PASSWORD_BCRYPT);"
-- =========================================================

INSERT INTO ROLE (NOM_ROLE, DESCRIPTION) VALUES ('ADMIN', 'Administrateur du portail');
INSERT INTO ROLE (NOM_ROLE, DESCRIPTION) VALUES ('UTILISATEUR', 'Utilisateur final');

INSERT INTO UTILISATEUR (NOM, PRENOM, EMAIL, MOT_DE_PASSE) VALUES
    ('Admin', 'Portail', 'admin@portail.local', '$2b$12$bsvX3J.57oWn0OGko1MGBuCIYkPfhcaep1aUsyx4YC59YkCjN8OVa');

INSERT INTO UTILISATEUR (NOM, PRENOM, EMAIL, MOT_DE_PASSE) VALUES
    ('Kahlouche', 'Ny', 'ny_kahlouche@esi.dz', '$2b$12$bsvX3J.57oWn0OGko1MGBuCIYkPfhcaep1aUsyx4YC59YkCjN8OVa');

INSERT INTO UTILISATEUR_ROLE (ID_UTILISATEUR, ID_ROLE)
    SELECT ID_UTILISATEUR, (SELECT ID_ROLE FROM ROLE WHERE NOM_ROLE = 'ADMIN')
    FROM UTILISATEUR WHERE EMAIL = 'admin@portail.local';

INSERT INTO UTILISATEUR_ROLE (ID_UTILISATEUR, ID_ROLE)
    SELECT ID_UTILISATEUR, (SELECT ID_ROLE FROM ROLE WHERE NOM_ROLE = 'UTILISATEUR')
    FROM UTILISATEUR WHERE EMAIL = 'ny_kahlouche@esi.dz';

INSERT INTO CATEGORIE (NOM_CATEGORIE, DESCRIPTION) VALUES ('Clients', 'Rapports lies aux clients');
INSERT INTO CATEGORIE (NOM_CATEGORIE, DESCRIPTION) VALUES ('Comptes', 'Rapports lies aux comptes bancaires');
INSERT INTO CATEGORIE (NOM_CATEGORIE, DESCRIPTION) VALUES ('Credits', 'Rapports lies aux credits');

INSERT INTO RAPPORT (TITRE, DESCRIPTION, ID_CATEGORIE, CHEMIN_CATALOGUE_OBI, FORMATS_DISPO)
VALUES ('Liste des comptes clients', 'Liste des clients avec leurs comptes associes', 1,
        '/~obi2026/LISTE COMPTES CLIENTS.xdo', 'pdf,excel2007,rtf');

INSERT INTO RAPPORT (TITRE, DESCRIPTION, ID_CATEGORIE, CHEMIN_CATALOGUE_OBI, FORMATS_DISPO)
VALUES ('Solde du compte', 'Consultation du solde d''un compte donne', 2,
        '/~obi2026/SOLDE DU COMPTE.xdo', 'pdf,excel2007,rtf');

-- Parametres pour "Liste des comptes clients" : plage de dates
INSERT INTO PARAMETRE_RAPPORT (ID_RAPPORT, NOM_PARAM_OBI, LIBELLE_AFFICHE, TYPE, OBLIGATOIRE)
    SELECT ID_RAPPORT, 'p_date_debut', 'Date de debut', 'DATE', 0
    FROM RAPPORT WHERE TITRE = 'Liste des comptes clients';

INSERT INTO PARAMETRE_RAPPORT (ID_RAPPORT, NOM_PARAM_OBI, LIBELLE_AFFICHE, TYPE, OBLIGATOIRE)
    SELECT ID_RAPPORT, 'p_date_fin', 'Date de fin', 'DATE', 0
    FROM RAPPORT WHERE TITRE = 'Liste des comptes clients';

-- Parametre pour "Solde du compte" : numero de compte obligatoire
INSERT INTO PARAMETRE_RAPPORT (ID_RAPPORT, NOM_PARAM_OBI, LIBELLE_AFFICHE, TYPE, OBLIGATOIRE)
    SELECT ID_RAPPORT, 'num_compte', 'Numero de compte', 'TEXTE', 1
    FROM RAPPORT WHERE TITRE = 'Solde du compte';

COMMIT;
