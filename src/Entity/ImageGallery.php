<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageGalleryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ImageGallery extends ImageField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="imageGalleries")
     */
    private $associateEvent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUploadDir()
    {
        return 'gallery';
    }

    public function getAssociateEvent(): ?Event
    {
        return $this->associateEvent;
    }

    public function setAssociateEvent(?Event $associateEvent): self
    {
        $this->associateEvent = $associateEvent;

        return $this;
    }

    public function __toString() : string
    {
        if ($this->name != null)
            return $this->name;
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
