<?php

namespace App\Entity;

use App\Repository\PosterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PosterEventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PosterEvent extends ImageField
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
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Poster", mappedBy="associateEvent")
     */
    private $posters;


    public function __construct()
    {
        $this->posters = new ArrayCollection();
        $this->date = new \DateTime();
        $this->position = 0;
    }

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|Poster[]
     */
    public function getPosters(): Collection
    {
        for($i = 0; $i < count($this->posters); $i++){
            for($j = $i+1; $j < count($this->posters); $j++){
                if ($this->posters[$j]->getPosition() < $this->posters[$i]->getPosition()){
                    $temp = $this->posters[$i];
                    $this->posters[$i] = $this->posters[$j];
                    $this->posters[$j] = $temp;
                }
            }
        }

        return $this->posters;
    }


    public function addPoster(Poster $poster): self
    {
        if (!$this->posters->contains($poster)) {
            $this->posters[] = $poster;
            $poster->setAssociateEvent($this);
        }

        return $this;
    }

    public function removePoster(Poster $poster): self
    {
        if ($this->posters->contains($poster)) {
            $this->posters->removeElement($poster);
            // set the owning side to null (unless already changed)
            if ($poster->getAssociateEvent() === $this) {
                $poster->setAssociateEvent(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    function getUploadDir()
    {
        return 'posterEvents';
    }
}
