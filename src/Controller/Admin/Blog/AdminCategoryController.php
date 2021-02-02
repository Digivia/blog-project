<?php

namespace App\Controller\Admin\Blog;

use App\Entity\Blog\Category;
use App\Controller\RouteCatalog;
use App\Exception\Category\CategoryNotDeletableException;
use App\Handler\Form\Category\CategoryFormHandler;
use App\Repository\CategoryRepositoryInterface;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog/category")
 */
final class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/", name=RouteCatalog::ADMIN_CATEGORY_INDEX, methods={"GET"})
     * @param Request $request
     * @param CategoryRepositoryInterface $categoryRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, CategoryRepositoryInterface $categoryRepository, PaginatorInterface $paginator): Response
    {
        $categories = $paginator->paginate(
            $categoryRepository->getAllChildCategoriesQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('@admin/blog/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/tree", name=RouteCatalog::ADMIN_CATEGORY_TREE, methods={"GET"})
     * @param CategoryRepositoryInterface $categoryRepository
     * @return Response
     */
    public function tree(CategoryRepositoryInterface $categoryRepository): Response
    {
        return $this->render('@admin/blog/category/tree.html.twig', [
            'categories' => $categoryRepository->getSameLevelCategories(),
        ]);
    }

    /**
     * @Route("/new", name=RouteCatalog::ADMIN_CATEGORY_NEW, methods={"GET","POST"})
     * @param Request $request
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function new(Request $request, HandlerFactoryInterface $factory): Response
    {
        $category = new Category();
        $handler  = $factory->createHandler(CategoryFormHandler::class);
        if ($handler->handle($request, $category, [], ['creation' => true])) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_CATEGORY_INDEX);
        }
        return $this->render('@admin/blog/category/new.html.twig', [
            'category' => $category,
            'form'     => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_CATEGORY_SHOW, methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('@admin/blog/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name=RouteCatalog::ADMIN_CATEGORY_EDIT, methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function edit(Request $request, Category $category, HandlerFactoryInterface $factory): Response
    {
        $handler = $factory->createHandler(CategoryFormHandler::class);
        if ($handler->handle($request, $category)) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_CATEGORY_INDEX);
        }
        return $this->render('@admin/blog/category/edit.html.twig', [
            'category' => $category,
            'form'     => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_CATEGORY_DELETE, methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @param CategoryRepositoryInterface $categoryRepository
     * @return Response
     */
    public function delete(Request $request, Category $category, CategoryRepositoryInterface $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            try {
                $categoryRepository->remove($category);
            } catch (CategoryNotDeletableException $categoryNotDeletableException) {
                $this->addFlash(
                    "error",
                    sprintf(
                        "La catagorie %s ne peut pas être supprimée car elle possède des enfants",
                        $categoryNotDeletableException->getCategory()->getName()
                    )
                );
            }
        }
        return $this->redirectToRoute(RouteCatalog::ADMIN_CATEGORY_INDEX);
    }
}
