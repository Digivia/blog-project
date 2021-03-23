<?php

declare(strict_types=1);

namespace App\Security\Roles;

/**
 * Class Roles
 * @package App\Security
 */
class Roles
{
    public const ROLE_USER        = 'ROLE_USER';
    public const ROLE_API         = 'ROLE_API';
    public const ROLE_CONTRIBUTOR = 'ROLE_CONTRIBUTOR';
    public const ROLE_EDITOR      = 'ROLE_EDITOR';
    public const ROLE_ADMIN       = 'ROLE_ADMIN';

    public const ROLE_HIERARCHY = [
        self::ROLE_CONTRIBUTOR => self::ROLE_USER,
        self::ROLE_EDITOR      => self::ROLE_CONTRIBUTOR,
        self::ROLE_ADMIN       => [self::ROLE_EDITOR, self::ROLE_API, 'ROLE_ALLOWED_TO_SWITCH'],
    ];

    public const ROLES = [
        self::ROLE_USER        => 'Utilisateur',
        self::ROLE_API         => 'Accès API',
        self::ROLE_CONTRIBUTOR => 'Contributeur',
        self::ROLE_EDITOR      => 'Rédacteur en chef',
        self::ROLE_ADMIN       => 'Administrateur',
    ];
}
