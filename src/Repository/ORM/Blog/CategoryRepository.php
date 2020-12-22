<?php

declare(strict_types=1);

namespace App\Repository\ORM\Blog;

use App\Entity\Blog\Category;
use App\Gateway\DoctrineBehavior\DoctrineRemoveAwareTrait;
use App\Gateway\DoctrineBehavior\DoctrineSaveAwareTrait;
use App\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CategoryRepository
 * @package App\Repository\ORM\Blog
 */
final class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    use DoctrineSaveAwareTrait, DoctrineRemoveAwareTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
