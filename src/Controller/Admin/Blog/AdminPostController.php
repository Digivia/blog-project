<?php

namespace App\Controller\Admin\Blog;

use App\Entity\Blog\Post;
use App\Handler\Form\Post\PostFormHandler;
use App\Handler\Post\PostStatusWorkflowHandler;
use App\Repository\PostRepositoryInterface;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RouteCatalog;

/**
 * @Route("/admin/blog/post")
 */
final class AdminPostController extends AbstractController
{
    /**
     * @Route("/", name=RouteCatalog::ADMIN_POST_INDEX, methods={"GET"}, defaults={"search": true})
     * @param Request $request
     * @param PostRepositoryInterface $postRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PostRepositoryInterface $postRepository, PaginatorInterface $paginator): Response
    {
        $posts = $paginator->paginate(
            $postRepository->getPostQuery($request->query->get('search-keyword')),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('@admin/blog/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name=RouteCatalog::ADMIN_POST_NEW, methods={"GET","POST"})
     * @param Request $request
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function new(Request $request, HandlerFactoryInterface $factory): Response
    {
        $post    = new Post();
        $handler = $factory->createHandler(PostFormHandler::class);
        if ($handler->handle($request, $post, [], ['creation' => true])) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_POST_INDEX);
        }
        return $this->render('@admin/blog/post/new.html.twig', [
            'post' => $post,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_POST_SHOW, methods={"GET"})
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        return $this->render('@admin/blog/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name=RouteCatalog::ADMIN_POST_EDIT, methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function edit(Request $request, Post $post, HandlerFactoryInterface $factory): Response
    {
        $handler = $factory->createHandler(PostFormHandler::class);
        if ($handler->handle($request, $post)) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_POST_INDEX);
        }
        return $this->render('@admin/blog/post/edit.html.twig', [
            'post' => $post,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/workflow/{action}/{id}", name=RouteCatalog::ADMIN_POST_WORKFLOW, methods={"POST"})
     * @param Post $post
     * @param string $action
     * @param Request $request
     * @param PostStatusWorkflowHandler $workflowHandler
     * @return Response
     */
    public function workflow(Post $post, string $action, Request $request, PostStatusWorkflowHandler $workflowHandler): Response
    {
        if (!method_exists($workflowHandler, $action)) {
            $this->createNotFoundException('This action does not exists');
        }
        if ($this->isCsrfTokenValid('workflow' . $post->getId(), $request->request->get('_token'))) {
            $workflowHandler->$action($post);
        }
        return $this->redirectToRoute(RouteCatalog::ADMIN_POST_INDEX);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_POST_DELETE, methods={"DELETE"})
     * @param Request $request
     * @param Post $post
     * @param PostRepositoryInterface $postRepository
     * @return Response
     */
    public function delete(Request $request, Post $post, PostRepositoryInterface $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }
        return $this->redirectToRoute(RouteCatalog::ADMIN_POST_INDEX);
    }
}
