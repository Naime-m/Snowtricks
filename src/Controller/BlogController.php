<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class BlogController extends AbstractController
{
    /**
     * @Route ("/", name= "home")
     */
    public function home(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->findAll();

        return $this->render('blog/index.html.twig', [
                'tricks' => $tricks,
            ]);
    }

    /**
     * @Route("/trick/{id}/{slug}/show", name="trick_show")
     */
    public function show(Trick $trick, Request $request, UserInterface $user = null, ManagerRegistry $entityManager)
    {
        $comment = new Comment();
        $pictures = $trick->getPictures();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDate(new \DateTime());
            $comment->setTrick($trick);
            $comment->setAuthor($user);
            $manager = $entityManager->getManager();
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->renderForm('blog/show.html.twig', [
            'trick' => $trick,
            'comment' => $comment,
            'formComment' => $form,
            'pictures' => $pictures,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/trick/new", name= "trick_new")
     * @Route("/trick/{id}/{slug}/edit", name="trick_edit")
     */
    public function new(Trick $trick = null, Request $request, ManagerRegistry $entityManager, FileUploader $fileUploader): Response
    {
        if (!$trick) {
            $trick = new Trick();
        }

        $originalPictures = new ArrayCollection();
        foreach ($trick->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$trick->getId()) {
                $trick->setDate(new \DateTime());
                $this->addFlash('success', 'La figure a bien été ajoutée !');
            }

            $trick->deleteAllPictures();

            /** @var Picture $picture */
            foreach ($originalPictures as $picture) {
                if (false === $trick->getPictures()->contains($picture)) {
                    $picture->setTrick(null);

                    $entityManager->getManager()->persist($picture);
                }
            }
            $pictures = $form->get('pictures');
            foreach ($pictures as $key => $picture) {
                /* @var UploadedFile $pictureFile */
                $pictureFile = $picture->get('link')->getData();
                if ($pictureFile) {
                    $pictureFileName = $fileUploader->upload($pictureFile);
                    $pict = new Picture();
                    $pict->setLink($pictureFileName);
                    $pict->setTrick($trick);
                    $trick->addPicture($pict);
                }

                $videos = $form->get('videos');
                foreach ($videos as $key => $video) {
                    $video = new Video();
                    $video->setTrick($trick);
                }
            }
            $manager = $entityManager->getManager();
            $manager->persist($trick);

            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('blog/new.html.twig', [
        'formTrick' => $form,
        'editMode' => null !== $trick->getId(),
        ]);
    }

    /**
     * @Route("/trick/{id}/{slug}/delete", name="trick_delete")
     */
    public function delete(Trick $trick, ManagerRegistry $entityManager, int $id)
    {
        $trick = $entityManager->getRepository(Trick::class)->find($id);
        $manager = $entityManager->getManager();
        $manager->remove($trick);
        $manager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
