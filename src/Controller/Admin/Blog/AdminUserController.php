<?php

namespace App\Controller\Admin\Blog;

use App\Entity\Blog\Post;
use App\Entity\User\User;
use App\Handler\Form\Post\PostFormHandler;
use App\Handler\Form\Post\UserFormHandler;
use App\Handler\Post\PostStatusWorkflowHandler;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Digivia\FormHandler\HandlerFactory\HandlerFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RouteCatalog;

/**
 * @Route("/admin/user")
 */
final class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name=RouteCatalog::ADMIN_USER_INDEX, methods={"GET"}, defaults={"search": true})
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, UserRepositoryInterface $userRepository, PaginatorInterface $paginator): Response
    {
        $users = $paginator->paginate(
            $userRepository->getUserQuery($request->query->get('search-keyword')),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('@admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name=RouteCatalog::ADMIN_USER_NEW, methods={"GET","POST"})
     * @param Request $request
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function new(Request $request, HandlerFactoryInterface $factory): Response
    {
        $user    = User::createByAdmin();
        $handler = $factory->createHandler(UserFormHandler::class);
        if ($handler->handle($request, $user, [], ['creation' => true])) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_USER_INDEX);
        }
        return $this->render('@admin/user/new.html.twig', [
            'user' => $user,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_USER_SHOW, methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('@admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name=RouteCatalog::ADMIN_USER_EDIT, methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param HandlerFactoryInterface $factory
     * @return Response
     */
    public function edit(Request $request, User $user, HandlerFactoryInterface $factory): Response
    {
        $handler = $factory->createHandler(UserFormHandler::class);
        if ($handler->handle($request, $user)) {
            return $this->redirectToRoute(RouteCatalog::ADMIN_USER_INDEX);
        }
        return $this->render('@admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $handler->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=RouteCatalog::ADMIN_USER_DELETE, methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @param UserRepositoryInterface $userRepository
     * @return Response
     */
    public function delete(Request $request, User $user, UserRepositoryInterface $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }
        return $this->redirectToRoute(RouteCatalog::ADMIN_USER_INDEX);
    }
}
