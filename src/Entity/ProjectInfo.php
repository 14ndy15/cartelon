<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectInfoRepository")
 */
class ProjectInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountNews;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPosterEvent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountEvents;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountVideos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountSlides;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $posterInfo;



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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAmountNews(): ?int
    {
        return $this->amountNews;
    }

    public function setAmountNews(?int $amountNews): self
    {
        $this->amountNews = $amountNews;

        return $this;
    }

    public function getAmountPosterEvent(): ?int
    {
        return $this->amountPosterEvent;
    }

    public function setAmountPosterEvent(?int $amountPosterEvent): self
    {
        $this->amountPosterEvent = $amountPosterEvent;

        return $this;
    }

    public function getAmountEvents(): ?int
    {
        return $this->amountEvents;
    }

    public function setAmountEvents(?int $amountEvents): self
    {
        $this->amountEvents = $amountEvents;

        return $this;
    }

    public function getAmountVideos(): ?int
    {
        return $this->amountVideos;
    }

    public function setAmountVideos(?int $amountVideos): self
    {
        $this->amountVideos = $amountVideos;

        return $this;
    }

    public function getAmountSlides(): ?int
    {
        return $this->amountSlides;
    }

    public function setAmountSlides(?int $amountSlides): self
    {
        $this->amountSlides = $amountSlides;

        return $this;
    }

    public function getPosterInfo(): ?string
    {
        return $this->posterInfo;
    }

    public function setPosterInfo(?string $posterInfo): self
    {
        $this->posterInfo = $posterInfo;

        return $this;
    }
}
