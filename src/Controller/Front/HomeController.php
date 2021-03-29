<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RouteCatalog;

final class HomeController extends AbstractController
{
    /**
     * @Route("/", name=RouteCatalog::FRONT_HOMEPAGE, methods={"GET"})
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render(
            '@front/home/index.html.twig', []
        );
    }
}
