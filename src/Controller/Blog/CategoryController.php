<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Category;
use App\Form\Blog\CategoryType;
use App\Handler\Form\Category\CategoryFormHandler;
use App\Repository\Blog\CategoryRepository;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="blog_category_index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('blog/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
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
        $handler = $factory->createHandler(CategoryFormHandler::class);
        if ($handler->handle($request, $category, [], ['creation' => true])) {
            return $this->redirectToRoute('blog_category_index');
        }
        return $this->render('blog/category/new.html.twig', [
            'category' => $category,
            'form' => $handler->createView(),
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
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_category_index');
        }

        return $this->render('blog/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_category_delete", methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_category_index');
    }
}
