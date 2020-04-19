<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false,                  onDelete="CASCADE")
     */
    private $tricksId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTricksId(): ?Tricks
    {
        return $this->tricksId;
    }

    public function setTricksId(?Tricks $tricksId): self
    {
        $this->tricksId = $tricksId;

        return $this;
    }
}
