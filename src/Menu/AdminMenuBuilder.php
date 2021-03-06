<?php

namespace App\Menu;

use App\Controller\RouteCatalog;
use App\Repository\CategoryRepositoryInterface;
use App\Security\Roles\Roles;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

class AdminMenuBuilder
{
    private FactoryInterface $factory;
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(FactoryInterface $factory, CategoryRepositoryInterface $categoryRepository, Security $security)
    {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
        $this->security = $security;
    }

    public function createMainAdminMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setExtra('translation_domain', 'menu');

        $menu
            ->addChild('menu.home', ['route' => RouteCatalog::ADMIN_DASHBOARD])
            ->setExtra('translation_domain', 'menu');
        $categoryMenu = $menu
            ->addChild('menu.categories.label', ['attributes' => ['dropdown' => true]])
            ->setExtra('translation_domain', 'menu');
        $postMenu = $menu
            ->addChild('menu.post.label', ['attributes' => ['dropdown' => true]])
            ->setExtra('translation_domain', 'menu');

        if ($this->security->isGranted(Roles::ROLE_ADMIN)) {
            $userMenu = $menu
                ->addChild('menu.user.label', ['attributes' => ['dropdown' => true]])
                ->setExtra('translation_domain', 'menu');
            // Handle User menu
            $userMenu
                ->addChild('menu.user.all', [
                    'route' => RouteCatalog::ADMIN_USER_INDEX
                ])
                ->setExtra('translation_domain', 'menu');
            $userMenu
                ->addChild('menu.user.new', [
                    'route' => RouteCatalog::ADMIN_USER_NEW
                ])
                ->setExtra('translation_domain', 'menu');
        }

        $menu->setChildrenAttributes(['class' => 'navbar-nav dgv-navbar-list']);

        // Handle Category menu
        $categoryMenu
            ->addChild('menu.categories.all', [
                'route' => RouteCatalog::ADMIN_CATEGORY_INDEX,
            ])
            ->setExtra('translation_domain', 'menu');
        $categoryMenu
            ->addChild('menu.categories.tree', [
                'route' => RouteCatalog::ADMIN_CATEGORY_TREE,
                'attributes' => [
                    'divider_append' => true
                ]
            ])
            ->setExtra('translation_domain', 'menu');

        foreach ($this->categoryRepository->getSameLevelCategories(1, 5) as $category) {
            $categoryMenu->addChild($category->getName(), [
                'route' => RouteCatalog::ADMIN_CATEGORY_SHOW,
                'routeParameters' => ['id' => $category->getId()]
            ]);
        }
        $categoryMenu
            ->addChild('menu.categories.new', [
                'route' => RouteCatalog::ADMIN_CATEGORY_NEW,
                'attributes' => [
                    'divider_prepend' => true
                ]
            ])
            ->setExtra('translation_domain', 'menu');

        // Handle Post menu
        $postMenu
            ->addChild('menu.post.all', [
                'route' => RouteCatalog::ADMIN_POST_INDEX
            ])
            ->setExtra('translation_domain', 'menu');
        $postMenu
            ->addChild('menu.post.new', [
                'route' => RouteCatalog::ADMIN_POST_NEW
            ])
            ->setExtra('translation_domain', 'menu');
        return $menu;
    }
}
