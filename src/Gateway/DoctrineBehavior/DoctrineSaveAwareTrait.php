<?php

declare(strict_types=1);

namespace App\Gateway\DoctrineBehavior;

/**
 * Trait DoctrineSaveAwareTrait
 * @package App\Gateway\DoctrineBehavior
 */
trait DoctrineSaveAwareTrait
{
    /**
     * @param object $entity : entity to save in doctrine data layer
     */
    public function save(object $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }
}
