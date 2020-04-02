<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
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
    private $Name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $TricksId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getTricksId(): ?Tricks
    {
        return $this->TricksId;
    }

    public function setTricksId(?Tricks $TricksId): self
    {
        $this->TricksId = $TricksId;

        return $this;
    }
}
