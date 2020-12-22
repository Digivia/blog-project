<?php

namespace App\Gateway;

use Doctrine\Persistence\ObjectRepository;

interface GatewayInterface extends ObjectRepository
{
    /**
     * Save Object in his data layer
     * @param object $entity : object to save
     */
    public function save(object $entity): void;

    /**
     * Remove Object from his data layer
     * @param object $entity
     */
    public function remove(object $entity): void;
}
