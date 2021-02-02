<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Blog\Category;
use App\Gateway\GatewayInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Category[]    count(array $criteria)
 */
interface CategoryRepositoryInterface extends GatewayInterface
{
    public function getRootNodesQueryBuilder($sortByField = null, $direction = 'asc');
    public function getRootNodes($sortByField = null, $direction = 'asc');

    /**
     * @param int $level
     * @param int|null $maxResults
     * @return Category[]
     */
    public function getSameLevelCategories(int $level = 1, int $maxResults = null): array;

    /**
     * @return Category[]
     */
    public function getAllChildCategories(): array;

    /**
     * Count all categories except root category
     * @return int
     */
    public function countAll(): int;
}
