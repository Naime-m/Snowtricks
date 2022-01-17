<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    private function category(ObjectManager $manager, $label)
    {
        $cat = new Category();
        $cat->setLabel($label);
        $manager->persist($cat);

        return $cat;
    }

    private function image(ObjectManager $manager, Trick $trick, $link)
    {
        $img = new Picture();
        $img->setLink($link);
        $img->setTrick($trick);
        $manager->persist($img);

        return $img;
    }

    private function video(ObjectManager $manager, Trick $trick, $link)
    {
        $embed = new Video();
        $embed->setLink($link);
        $embed->setTrick($trick);
        $manager->persist($embed);

        return $embed;
    }

    private function trick(ObjectManager $manager, $name, $description, $cat, $imageLink, $embed)
    {
        $trick = new Trick();
        $trick->setName($name);
        $trick->setDescription($description);
        $trick->setDate(new \DateTime());
        $trick->setCategory($cat);
        $trick->addPicture($this->image($manager, $trick, $imageLink));
        $trick->addVideo($this->video($manager, $trick, $embed));
        // ajout image
        $manager->persist($trick);

        return $trick;
    }

    public function load(ObjectManager $manager): void
    {
        /* Categories */
        $grab = $this->category($manager, 'Grab');
        $rotation = $this->category($manager, 'Rotation');
        $flip = $this->category($manager, 'Flip');
        $slide = $this->category($manager, 'Slide');

        /* Figures */
        $fig1 = $this->trick($manager, '360', 'Rotation horizontale avec un tour complet', $rotation, 'img1.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig2 = $this->trick($manager, 'Indy', 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière', $grab, 'img2.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig3 = $this->trick($manager, 'Front Flip', 'Rotation verticale en avant', $flip, 'img3.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig4 = $this->trick($manager, 'Nose Slide', 'Glisse avec l\'avant de la planche sur la barre', $slide, 'img4.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig5 = $this->trick($manager, '180', 'Demi-tour à l\'horizontale', $rotation, 'img5.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig6 = $this->trick($manager, 'Mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant', $grab, 'img6.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig7 = $this->trick($manager, 'Seat Belt', 'Saisie du carre frontside à l\'arrière avec la main avant', $grab, 'img7.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig8 = $this->trick($manager, 'Sad ', 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant', $grab, 'img8.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig9 = $this->trick($manager, '720', 'Deux tours complets à l\'horizontale', $rotation, 'img9.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $fig10 = $this->trick($manager, 'Big Foot', 'Trois tours complets à l\'horizontale', $rotation, 'img10.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');

        $manager->flush();
    }
}
