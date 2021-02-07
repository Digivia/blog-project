<?php

declare(strict_types=1);

namespace App\Security\Roles;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RoleUtils
 * @package App\Security\Roles
 */
class RoleUtils
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get Roles with labels for a form choice type field
     * @return array
     */
    public function getChoicesToForm(): array
    {
        $roles = $this->getRolesWithLabel();
        dump(array_flip($roles));
        return array_flip($roles);
    }

    /**
     * Get Roles and their labels
     * @return array
     */
    public function getRolesWithLabel(): array
    {
        $roles = Roles::ROLES;
        foreach ($roles as $role => &$label) {
            $translation = $this->translator->trans($role, [], 'roles');
            if ($role !== $translation && strlen($translation)) {
                $label = $translation;
            }
        }
        return $roles;
    }
}