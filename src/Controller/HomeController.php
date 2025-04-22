<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAllWithMedia();
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ] );
    }

    #[Route('/unused', name: 'unused')]
    public function unused(): Response
    {
        return $this->render('home/unused.html.twig', ['controller_name' => 'BlogController']);
    }

}