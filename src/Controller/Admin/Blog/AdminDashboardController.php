<?php

declare(strict_types=1);

namespace App\Controller\Admin\Blog;

use App\Repository\CategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RouteCatalog;

final class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/blog/dashboard", name=RouteCatalog::ADMIN_DASHBOARD, methods={"GET"})
     * @param PostRepositoryInterface $postRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @return Response
     */
    public function __invoke(PostRepositoryInterface $postRepository, CategoryRepositoryInterface $categoryRepository): Response
    {
        return $this->render(
            '@admin/blog/dashboard/index.html.twig',
            [
                'post_nr' => $postRepository->countPostByStatus(),
                'publish_post_nr' => $postRepository->countPostByStatus('published'),
                'draft_post_nr' => $postRepository->countPostByStatus('draft'),
                'trash_post_nr' => $postRepository->countPostByStatus('trash'),
                'category_nr' => $categoryRepository->countAll(),
            ]
        );
    }
}
