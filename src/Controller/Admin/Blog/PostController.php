<?php

namespace App\Controller\Admin\Blog;

use App\Entity\Blog\Post;
use App\Handler\Form\Post\PostFormHandler;
use App\Repository\PostRepositoryInterface;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/post")
 */
final class PostController extends AbstractController
{
    /**
     * @Route("/", name="blog_post_index", methods={"GET"})
     * @param PostRepositoryInterface $postRepository
     * @return Response
     */
    public function index(PostRepositoryInterface $postRepository): Response
    {
        return $this->render('blog/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="blog_post_new", methods={"GET","POST"})
     * @param Request $request
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function new(Request $request, HandlerFactoryInterface $factory): Response
    {
        $post = new Post();
        $handler = $factory->createHandler(PostFormHandler::class);
        if ($handler->handle($request, $post, [], ['creation' => true])) {
            return $this->redirectToRoute('blog_post_index');
        }
        return $this->render('blog/post/new.html.twig', [
            'post' => $post,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_show", methods={"GET"})
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        return $this->render('blog/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_post_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function edit(Request $request, Post $post, HandlerFactoryInterface $factory): Response
    {
        $handler = $factory->createHandler(PostFormHandler::class);
        if ($handler->handle($request, $post)) {
            return $this->redirectToRoute('blog_post_index');
        }
        return $this->render('blog/post/edit.html.twig', [
            'post' => $post,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_delete", methods={"DELETE"})
     * @param Request $request
     * @param Post $post
     * @param PostRepositoryInterface $postRepository
     * @return Response
     */
    public function delete(Request $request, Post $post, PostRepositoryInterface $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }
        return $this->redirectToRoute('blog_post_index');
    }
}
