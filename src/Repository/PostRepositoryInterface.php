<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Blog\Post;
use App\Gateway\GatewayInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Post[]    count(array $criteria)
 */
interface PostRepositoryInterface extends GatewayInterface
{
    /**
     * Count post for a specific status, or for all status if null
     * @param string|null $status
     * @return int
     */
    public function countPostByStatus(string $status = null): int;
}
