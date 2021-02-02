<?php

declare(strict_types=1);

namespace App\Repository\ORM\Blog;

use App\Entity\Blog\Category;
use App\Exception\BadEntityObjectException;
use App\Exception\Category\CategoryNotDeletableException;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\ORM\Utils\EntityPropertyChangedHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class CategoryRepository
 * @package App\Repository\ORM\Blog
 */
final class CategoryRepository extends NestedTreeRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     * Use previous Symfony Repository implementation to be compliant with
     * Doctrine Extension bundle
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Category::class));
    }

    /**
     * @param Category $entity
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws BadEntityObjectException
     */
    public function save(object $entity): void
    {
        $this->checkEntity($entity);
        // If no parent, push it in main root category
        if (null === $entity->getParent()) {
            $entity->setParent($this->getMainRootCategory());
        }
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param Category $entity
     * @throws BadEntityObjectException
     * @throws CategoryNotDeletableException
     * @throws ORMException
     */
    public function remove(object $entity): void
    {
        $this->checkEntity($entity);
        if ($entity->getChildren()->count()) {
            throw new CategoryNotDeletableException($entity);
        }
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * @param int $level
     * @return QueryBuilder
     */
    public function getSameLevelCategoriesQB(int $level = 1): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.lvl = :level')
            ->setParameter('level', $level);
    }

    /**
     * @inheritDoc
     */
    public function getSameLevelCategories(int $level = 1, int $maxResults = null): array
    {
        $query = $this
            ->getSameLevelCategoriesQB();
        // Max result if needed
        if (null !== $maxResults) {
            $query->setMaxResults($maxResults);
        }
        return (array) $query->getQuery()->getResult();
    }

    /**
     * @return Category
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getMainRootCategory(): Category
    {
        $mainCategory = $this->findOneBy(['lvl' => 0]);
        if (null === $mainCategory) {
            $mainCategory = new Category();
            $mainCategory->setName("ROOT");
            $this->_em->persist($mainCategory);
            $this->_em->flush();
            $this->_em->refresh($mainCategory);
        }
        return $mainCategory;
    }

    /**
     * @param bool $enabledOnly
     * @return QueryBuilder
     */
    public function getAllChildCategoriesQuery(bool $enabledOnly = false): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
                    ->where('c.lvl > 0');
        if (true === $enabledOnly) {
            $qb->andWhere('c.enabled = 1');
        }
        $qb->orderBy('c.lft', 'ASC');
        return $qb;
    }

    /**
     * @inheritDoc
     */
    public function getAllChildCategories(): array
    {
        return $this->getAllChildCategoriesQuery()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param object $entity
     * @return void
     * @throws BadEntityObjectException
     */
    public function checkEntity(object $entity): void
    {
        if (!$entity instanceof Category) {
            throw new BadEntityObjectException(
                sprintf(
                    "Repository %s handle only entity of class %s",
                    self::class,
                    Category::class
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function countAll(): int
    {
        try {
            return (int) $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.lvl > 0')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
