<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\NewTricksForm;
use App\Form\CommentType;
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

        if ($trick->getVideos()->isEmpty()) {
            $trick->addVideo(new \App\Entity\Video());
        }

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
    public function show(string $slug, EntityManagerInterface $em, Request $request): Response
    {
        $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé');
        }

        // Toujours définir $form avant le return
        $comment = new Comment();
        $comment->setTrick($trick);

        if ($this->getUser()) {
            $comment->setUser($this->getUser());
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');

            return $this->redirectToRoute('trick_show', ['slug' => $slug]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $form->createView(),
        ]);
    }

}