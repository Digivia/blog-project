<?php

namespace App\Controller\Admin\Blog;

use App\Entity\Blog\Category;
use App\Exception\Category\CategoryNotDeletableException;
use App\Handler\Form\Category\CategoryFormHandler;
use App\Repository\CategoryRepositoryInterface;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/category")
 */
final class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="blog_category_index", methods={"GET"})
     * @param CategoryRepositoryInterface $categoryRepository
     * @return Response
     */
    public function index(CategoryRepositoryInterface $categoryRepository): Response
    {
        return $this->render('blog/category/index.html.twig', [
            'categories' => $categoryRepository->getSameLevelCategories(),
        ]);
    }

    /**
     * @Route("/tree", name="blog_category_tree", methods={"GET"})
     * @param CategoryRepositoryInterface $categoryRepository
     * @return Response
     */
    public function tree(CategoryRepositoryInterface $categoryRepository): Response
    {
        return $this->render('blog/category/tree.html.twig', [
            'categories' => $categoryRepository->getSameLevelCategories(),
        ]);
    }

    /**
     * @Route("/new", name="blog_category_new", methods={"GET","POST"})
     * @param Request $request
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function new(Request $request, HandlerFactoryInterface $factory): Response
    {
        $category = new Category();
        $handler  = $factory->createHandler(CategoryFormHandler::class);
        if ($handler->handle($request, $category, [], ['creation' => true])) {
            return $this->redirectToRoute('blog_category_index');
        }
        return $this->render('blog/category/new.html.twig', [
            'category' => $category,
            'form'     => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_category_show", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('blog/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_category_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function edit(Request $request, Category $category, HandlerFactoryInterface $factory): Response
    {
        $handler = $factory->createHandler(CategoryFormHandler::class);
        if ($handler->handle($request, $category)) {
            return $this->redirectToRoute('blog_category_index');
        }
        return $this->render('blog/category/edit.html.twig', [
            'category' => $category,
            'form'     => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_category_delete", methods={"DELETE"})
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
        return $this->redirectToRoute('blog_category_index');
    }
}
