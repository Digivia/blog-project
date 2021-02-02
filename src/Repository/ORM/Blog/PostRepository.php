<?php

declare(strict_types=1);

namespace App\Repository\ORM\Blog;

use App\Entity\Blog\Post;
use App\Gateway\DoctrineBehavior\DoctrineRemoveAwareTrait;
use App\Repository\PostRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    use DoctrineRemoveAwareTrait;

    private WorkflowInterface $workflow;

    public function __construct(ManagerRegistry $registry, WorkflowInterface $blogPublishStateMachine)
    {
        parent::__construct($registry, Post::class);
        $this->workflow = $blogPublishStateMachine;
    }

    /**
     * @inheritDoc
     */
    public function save(object $entity): void
    {
        // Workflow initial state if needed
        $this->workflow->getMarking($entity);
        // Save in Db
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param string|null $search
     * @param array|string[] $allowedStatus
     * @return Query
     */
    public function getPostQuery(string $search = null, array $allowedStatus = ['all']): Query
    {
        $qb = $this->createQueryBuilder('p');
        // Search by keyword
        if (null !== $search && strlen($search)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.title', ':search'),
                    $qb->expr()->like('p.digest', ':search'),
                    $qb->expr()->like('p.content', ':search')
                ))
                ->setParameter('search', "%{$search}%");
        }
        // filter status
        if (!in_array('all', $allowedStatus) && count($allowedStatus)) {
            $qb->andWhere('p.status IN (:status)')
                ->setParameter('status', implode(',', $allowedStatus));
        }
        return $qb->getQuery();
    }
}
