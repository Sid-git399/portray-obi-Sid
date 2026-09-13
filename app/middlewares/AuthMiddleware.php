<?php

namespace App\Middlewares;

use App\Core\Auth;

class AuthMiddleware
{
    public static function handle(array $rolesAutorises = ['ADMIN', 'UTILISATEUR']): void
    {
        Auth::requireRole($rolesAutorises);
    }
}
