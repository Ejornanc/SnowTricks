<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Image;
use App\Form\NewTricksForm;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class TrickController extends AbstractController
{
    #[Route('/trick', name: 'trick')]
    public function home(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAllWithMedia();
        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks
        ] );
    }


    #[Route('/trick/new', name: 'trick_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $trick = new Trick();

        // Toujours au moins un champ image visible
        if ($trick->getImages()->isEmpty()) {
            $trick->addImage(new Image());
        }

        $form = $this->createForm(NewTricksForm::class, $trick);
        $form->handleRequest($request);

        // Si soumis mais pas valide (ex. champ vide), on ajoute un champ vide SI le dernier est rempli
        if ($form->isSubmitted() && !$form->isValid()) {
            $images = $trick->getImages();

            if (!$images->isEmpty()) {
                $lastImage = $images->last();
                if ($lastImage?->getImageFile()) {
                    $trick->addImage(new Image());

                    // Re-création du formulaire avec la nouvelle image ajoutée
                    $form = $this->createForm(NewTricksForm::class, $trick);
                    $form->handleRequest($request);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUser($this->getUser());
            $slug = $slugger->slug($trick->getName())->lower();
            $trick->setSlug($slug);

            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('trick_show', ['slug' => $slug]);
        }

        return $this->render('trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trick/{slug}', name: 'trick_show')]
    public function show(string $slug, EntityManagerInterface $entityManager): Response
    {
        $trick = $entityManager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé');
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

}