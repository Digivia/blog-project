<?php

declare(strict_types=1);

namespace App\Controller\Admin\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RouteCatalog;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/blog/dashboard", name=RouteCatalog::ADMIN_DASHBOARD, methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('admin/blog/dashboard/index.html.twig');
    }
}
