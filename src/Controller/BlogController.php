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
        foreach ($tricks as $key => $trick) {
            $pictures = $trick->getPictures();
        }

        return $this->render('blog/index.html.twig', [
                'tricks' => $tricks,
                'pictures' => $pictures,
            ]);
    }

    /**
     * @Route("/trick/{id}/show", name="trick_show")
     */
    public function show(Trick $trick, Request $request, UserInterface $user = null, ManagerRegistry $entityManager)
    {
        $comment = new Comment();
        $pictures = $trick->getPictures();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $user->getUserIdentifier();
            $comment->setDate(new \DateTime());
            $comment->setTrick($trick);
            $comment->setAuthor($name);
            $manager = $entityManager->getManager();
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->renderForm('blog/show.html.twig', [
            'trick' => $trick,
            'comment' => $comment,
            'formComment' => $form,
            'pictures' => $pictures,
        ]);
    }

    /**
     * @Route("/trick/new", name= "trick_new")
     * @Route("/trick/{id}/edit", name="trick_edit")
     */
    public function new(Trick $trick = null, Request $request, ManagerRegistry $entityManager, FileUploader $fileUploader): Response
    {
        if (!$trick) {
            $trick = new Trick();
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$trick->getId()) {
                $trick->setDate(new \DateTime());
            }

            $trick->deleteAllPictures();
            $pictures = $form->get('pictures');
            foreach ($pictures as $key => $picture) {
                /** @var UploadedFile $pictureFile */
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

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->renderForm('blog/new.html.twig', [
        'formTrick' => $form,
        'editMode' => null !== $trick->getId(),
        ]);
    }

    /**
     * @Route("/trick/{id}/delete", name="trick_delete")
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
