<?php

namespace App\Api\Category\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Blog\Category;
use App\Repository\CategoryRepositoryInterface;

class CategoryDataPersister implements ContextAwareDataPersisterInterface
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Category;
    }

    public function persist($data, array $context = [])
    {
        $this->categoryRepository->save($data);
        return $data;
    }

    public function remove($data, array $context = []): void
    {
        $this->categoryRepository->remove($data);
    }
}
