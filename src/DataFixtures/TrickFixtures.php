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
        $fig = $this->trick($manager, '360', 'Saut avec une rotation complète', $rotation, 'telechargement-61e1a64d23342.jpg', '<iframe width="1350" height="526" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');

        $manager->flush();
    }
}
