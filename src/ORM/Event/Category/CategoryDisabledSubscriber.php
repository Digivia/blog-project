<?php

declare(strict_types=1);

namespace App\ORM\Event\Category;

use App\Entity\Blog\Category;
use App\ORM\Utils\EntityPropertyChanged;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Class CategoryDisabledSubscriber
 * @package App\ORM\Event\Category
 */
class CategoryDisabledSubscriber implements EventSubscriber
{
    private EntityPropertyChanged $ormChecker;

    public function __construct(EntityPropertyChanged $ormChecker)
    {
        $this->ormChecker = $ormChecker;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->disableCategory($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->disableCategory($args);
    }

    /**
     * Check if a category is disabled and if it has sub-categories, update it too
     * @param LifecycleEventArgs $args
     */
    private function disableCategory(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Category || true === $entity->getEnabled()) {
            return;
        }
        $em = $args->getObjectManager();
        if ($this->ormChecker->checkFieldChanged('enabled', $entity)) {
            $children = $entity->getChildren();
            /** @var Category $subCat */
            foreach ($children as $subCat) {
                $subCat->setEnabled(false);
                $em->persist($subCat);
                $em->flush();
            }
        }
    }
}
