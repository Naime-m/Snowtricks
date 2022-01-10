<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="text")
     */
    private $link;

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="pictures")
     */
    private $trick;

    public function getTrick(): string
    {
        return $this->trick;
    }

    public function setTrick($trick): void
    {
        $this->trick = $trick;
    }
}
