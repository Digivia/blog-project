<?php

namespace App\Api\Category\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Blog\Category;
use App\Repository\CategoryRepositoryInterface;

class CategoryCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private CategoryRepositoryInterface $categoryRepository;
    private PaginationExtension         $paginationExtension;

    public function __construct(CategoryRepositoryInterface $categoryRepository, PaginationExtension $paginationExtension)
    {
        $this->categoryRepository  = $categoryRepository;
        $this->paginationExtension = $paginationExtension;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Category::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $queryBuilder = $this->categoryRepository->getAllChildCategoriesQuery();

        $this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface
            && $this->paginationExtension->supportsResult($resourceClass, $operationName, $context)) {

            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $context);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
