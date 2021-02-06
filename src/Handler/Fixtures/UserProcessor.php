<?php

declare(strict_types=1);

namespace App\Handler\Fixtures;

use App\Entity\User\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserProcessor
 * @package App\Handler\Fixtures
 */
class UserProcessor implements ProcessorInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Processes an object before it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param object $object
     */
    public function preProcess(string $id, $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        /** @var User $object */
        $object->setPassword($this->passwordEncoder->encodePassword($object, $object->getPlainPassword()));
    }

    /**
     * Processes an object after it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param object $object
     */
    public function postProcess(string $id, $object): void
    {
        // TODO: Implement postProcess() method.
    }
}