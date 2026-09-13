<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['id_utilisateur']);
    }

    public static function user(): ?array
    {
        return self::check() ? [
            'id'   => $_SESSION['id_utilisateur'],
            'nom'  => $_SESSION['nom'],
            'role' => $_SESSION['role'],
        ] : null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole(array $rolesAutorises): void
    {
        self::requireLogin();
        if (!in_array($_SESSION['role'], $rolesAutorises, true)) {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    public static function logAction(string $action): void
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare(
                "INSERT INTO JOURNAL_ACTIVITE (ID_UTILISATEUR, ACTION, ADRESSE_IP) VALUES (:id, :action, :ip)"
            );
            $stmt->execute([
                'id'     => $_SESSION['id_utilisateur'] ?? null,
                'action' => $action,
                'ip'     => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ]);
        } catch (\Throwable $e) {
            error_log('logAction failed: ' . $e->getMessage());
        }
    }
}
