<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route ("/", name= "home")
     */
    public function home(TrickRepository $trickRepository)
    {
        return $this->render('blog/index.html.twig', [
                'tricks' => $trickRepository->findAll(),
            ]);
    }

    /**
     * @Route("/trick/{id}/show", name="trick_show")
     */
    public function show(Trick $trick)
    {
        return $this->render('blog/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/trick/new", name= "trick_new")
     * @Route("/trick/{id}/edit", name="trick_edit")
     */
    public function new(Request $request, ManagerRegistry $entityManager): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$trick->getId()) {
                $trick->setDate(new \DateTime());
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
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
