<?php

declare(strict_types=1);

namespace App\ORM\Utils;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityPropertyChanged
 * @package App\ORM\Utils
 */
class EntityPropertyChanged
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $fieldToCheck
     * @param $entity
     * @return bool
     */
    public function checkFieldChanged(string $fieldToCheck, $entity): bool
    {
        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets();
        $changes = \array_keys($uow->getEntityChangeSet($entity));
        if (\in_array($fieldToCheck, $changes)) {
            return true;
        }
        return false;
    }
}
