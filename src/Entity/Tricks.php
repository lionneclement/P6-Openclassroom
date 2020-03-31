<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 */
class Tricks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="TricksId")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="TricksId")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Style", mappedBy="tricks")
     */
    private $StyleId;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->StyleId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setTricksId($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getTricksId() === $this) {
                $photo->setTricksId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTricksId($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTricksId() === $this) {
                $video->setTricksId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Style[]
     */
    public function getStyleId(): Collection
    {
        return $this->StyleId;
    }

    public function addStyleId(Style $styleId): self
    {
        if (!$this->StyleId->contains($styleId)) {
            $this->StyleId[] = $styleId;
            $styleId->setTricks($this);
        }

        return $this;
    }

    public function removeStyleId(Style $styleId): self
    {
        if ($this->StyleId->contains($styleId)) {
            $this->StyleId->removeElement($styleId);
            // set the owning side to null (unless already changed)
            if ($styleId->getTricks() === $this) {
                $styleId->setTricks(null);
            }
        }

        return $this;
    }
}
