<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/figures", name="figures")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route ("/", name= "home")
     */
    public function home(TrickRepository $trickRepository, PictureRepository $pictureRepository)
    {
        return $this->render('blog/home.html.twig', [
                'tricks' => $trickRepository->findAll(),
                'pictures' => $pictureRepository->findAll(),
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
