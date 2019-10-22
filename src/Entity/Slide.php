<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SlideRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Slide extends ImageField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    private $tempBackground;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $pathBackground;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileBackground;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime;

    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->position = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUploadDir()
    {
        return 'slides';
    }


    private $wide_dimensionsFileBackground;
    private $filenamesFileBackground;

    public function loadDataFileBackground()
    {
        /*This array need to be sortes ascendent*/
        $this->wide_dimensionsFileBackground = [20, 500, 800, 1200, 1900, 3600];

        $this->filenamesFileBackground = [];
        foreach ($this->wide_dimensionsFileBackground as $wide)
            array_push($this->filenamesFileBackground, $this->getFileBackgroundAbsolutePath().'-'.$wide.'.jpg');
    }

    public function setFileBackground(UploadedFile $file = null)
    {
        $this->fileBackground = $file;
        // check if we have an old image path
        if (isset($this->pathBackground)) {
            // store the old name to delete after the update
            $this->tempBackground = $this->pathBackground;
            $this->pathBackground = null;
        } else {
            $this->pathBackground = 'initial';
        }

        return $this;
    }

    public function getFileBackground()
    {
        return $this->fileBackground;
    }


    public function getFileBackgroundAbsolutePath()
    {
        return null === $this->pathBackground
            ? null
            : $this->getFileBackgroundUploadRootDir().'/'.$this->pathBackground;
    }

    public function getFileBackgroundWebPath()
    {
        $prefixPath = 'uploads/images/slides/backgrounds/';
        $path = $prefixPath.$this->pathBackground;

        return $path;
    }

    protected function getFileBackgroundUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return  __DIR__ . '/../../public/uploads/images/slides/backgrounds/';
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFileBackground()
    {
        if (null === $this->getFileBackground()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileBackground()->move($this->getFileBackgroundUploadRootDir(), $this->pathBackground);
        $this->createThumbFileBackground();

        // check if we have an old file
        if (isset($this->tempBackground)) {
            // delete the old image
            @unlink($this->getFileBackgroundUploadRootDir().'/'.$this->tempBackground);
            $this->removeThumbsFileBackground();
            // clear the temp image path
            $this->tempBackground = null;
        }
        $this->fileBackground = null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadBackground()
    {
        if (null !== $this->getFileBackground()) {
            // haz lo que quieras para generar un nombre único
            $filename = substr(sha1(uniqid(mt_rand(), true)),0,10);
            $this->pathBackground = $filename.'.'.$this->getFileBackground()->guessExtension();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadBackground()
    {
        if ($file = $this->getFileBackgroundAbsolutePath()) {
            @unlink($file);
            $this->removeThumbsFileBackground();
        }
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPathFileBackground($path)
    {
        $this->pathBackground = $path;

        return $this;
    }

    private function createThumbFileBackground()
    {
        $this->loadDataFileBackground();
        $image = null;
        if(strpos($this->getFileBackgroundAbsolutePath(),".png"))
            $image = @imagecreatefrompng($this->getFileBackgroundAbsolutePath());
        else
            $image = @imagecreatefromjpeg($this->getFileBackgroundAbsolutePath());

        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $height / $width;
        for($i = 0; $i<count($this->wide_dimensionsFileBackground); $i++)
        {
            $thumb_width = $this->wide_dimensionsFileBackground[$i];
            $thumb_height = $original_aspect*$thumb_width;

            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

            imagecopyresampled($thumb,
                $image,
                0, 0,
                0, 0,
                $thumb_width, $thumb_height,
                $width, $height);

            imageinterlace($thumb, true);
            @imagejpeg($thumb, $this->filenamesFileBackground[$i], 80);

        }
    }

    public function removeThumbsFileBackground()
    {
        $this->loadDataFileBackground();
        if($file = $this->getFileBackgroundAbsolutePath())
        {
            foreach ($this->filenamesFileBackground as $filename)
                @unlink($filename);
        }
    }

    public function updateThumbsFileBackground(){
        $this->removeThumbsFileBackground();
        $this->createThumbFileBackground();
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPathFile()
    {
        return $this->pathBackground;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

}
