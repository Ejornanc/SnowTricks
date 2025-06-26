<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Comment;
use App\Entity\Video;
use App\Form\NewTricksForm;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        $form = $this->createForm(NewTricksForm::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $images = $trick->getImages();
            if (!$images->isEmpty()) {
                $lastImage = $images->last();
                if ($lastImage?->getImageFile()) {
                    $trick->addImage(new Image());
                    $form = $this->createForm(NewTricksForm::class, $trick);
                    $form->handleRequest($request);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer les images vides
            foreach ($trick->getImages() as $image) {
                if ($image->getImageFile() === null) {
                    $trick->removeImage($image);
                } else {
                    $image->setTrick($trick); // utile si le setTrick n’est pas automatique
                }
            }

            $trick->setUser($this->getUser());
            $cleanName = strip_tags($trick->getName());
            $slug = $slugger->slug($cleanName)->lower();
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

    #[Route('/trick/{slug}/delete', name: 'trick_delete', methods: ['POST'])]
    public function delete(string $slug, EntityManagerInterface $em, Request $request): Response
    {
        $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé');
        }

        // Protection CSRF
        if ($this->isCsrfTokenValid('delete_trick_' . $trick->getId(), $request->request->get('_token'))) {
            $em->remove($trick);
            $em->flush();
            $this->addFlash('success', 'Trick supprimé avec succès.');
        }

        return $this->redirectToRoute('home');
    }

}