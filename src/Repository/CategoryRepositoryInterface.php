<?php

namespace App\Repository;

use App\Entity\Blog\Category;
use App\Gateway\GatewayInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface CategoryRepositoryInterface extends GatewayInterface
{
    public function getRootNodesQueryBuilder($sortByField = null, $direction = 'asc');
    public function getRootNodes($sortByField = null, $direction = 'asc');
    public function getSameLevelCategories(int $level = 1): array;
}
