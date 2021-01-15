<?php


namespace App\Api\Category\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Blog\Category;
use App\Exception\Category\CategoryNotAvailableException;
use App\Repository\CategoryRepositoryInterface;

class CategoryItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Category::class === $resourceClass;
    }

    /**
     * @param string $resourceClass
     * @param array|int|string $id
     * @param string|null $operationName
     * @param array $context
     * @return Category|object|null
     * @throws CategoryNotAvailableException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): Category
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        if (null === $category || !$category instanceof Category) {
            throw new CategoryNotAvailableException("La catégorie demandée n'existe pas");
        }
        if ($category->getLvl() <= 0) {
            throw new CategoryNotAvailableException("Cette catégorie n'est pas visible");
        }
        return $category;
    }
}
