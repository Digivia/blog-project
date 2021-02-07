<?php

declare(strict_types=1);

namespace App\Repository\ORM\User;

use App\Entity\User\User;
use App\Gateway\DoctrineBehavior\DoctrineRemoveAwareTrait;
use App\Gateway\DoctrineBehavior\DoctrineSaveAwareTrait;
use App\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository
 * @package App\Repository\ORM\User
 */
final class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    use DoctrineSaveAwareTrait, DoctrineRemoveAwareTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->save($user);
    }

    /**
     * @param string|null $search
     * @return Query
     */
    public function getUserQuery(string $search = null): Query
    {
        $qb = $this->createQueryBuilder('u');
        // Search by keyword
        if (null !== $search && strlen($search)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.email', ':search'),
                    $qb->expr()->like('u.firstname', ':search'),
                    $qb->expr()->like('u.lastname', ':search')
                ))
                ->setParameter('search', "%{$search}%");
        }
        return $qb->getQuery();
    }
}
