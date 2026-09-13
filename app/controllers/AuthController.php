<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Utilisateur;

class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: /catalogue');
            exit;
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $motDePasse === '') {
            $erreur = "Merci de remplir tous les champs.";
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        $utilisateur = Utilisateur::findByEmail($email);

        if (!$utilisateur || (int) $utilisateur['ACTIF'] !== 1 || !password_verify($motDePasse, $utilisateur['MOT_DE_PASSE'])) {
            $erreur = "Identifiants incorrects.";
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['id_utilisateur'] = $utilisateur['ID_UTILISATEUR'];
        $_SESSION['nom']            = $utilisateur['PRENOM'] . ' ' . $utilisateur['NOM'];
        $_SESSION['role']           = $utilisateur['NOM_ROLE'];

        Auth::logAction('CONNEXION');

        header('Location: /catalogue');
        exit;
    }

    public function logout(): void
    {
        Auth::logAction('DECONNEXION');
        session_destroy();
        header('Location: /');
        exit;
    }
}
