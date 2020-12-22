<?php

declare(strict_types=1);

namespace App\Gateway\DoctrineBehavior;

/**
 * Trait DoctrineRemoveAwareTrait
 * @package App\Gateway\DoctrineBehavior
 */
trait DoctrineRemoveAwareTrait
{
    /**
     * @param object $entity : entity to save in doctrine data layer
     */
    public function remove(object $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
