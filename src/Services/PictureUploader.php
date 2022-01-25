<?php

namespace App\Service;

use App\Entity\Picture;
use App\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class PictureUploader
{
    public function upload(Trick $trick, ManagerRegistry $entityManager)
    {
        $trick->deleteAllPictures();
        $originalPictures = new ArrayCollection();
        foreach ($trick->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        /** @var Picture $picture */
        foreach ($originalPictures as $picture) {
            if (false === $trick->getPictures()->contains($picture)) {
                $picture->setTrick(null);
                $entityManager->getManager()->persist($picture);
            }
        }
    }
}
