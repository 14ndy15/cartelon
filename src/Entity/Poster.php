<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PosterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Poster extends ImageField
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
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PosterEvent", inversedBy="posters")
     */
    private $associateEvent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $searchableText;


    public function  __construct()
    {
        $this->date = new \DateTime();
    }

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAssociateEvent(): ?PosterEvent
    {
        return $this->associateEvent;
    }

    public function setAssociateEvent(?PosterEvent $associateEvent): self
    {
        $this->associateEvent = $associateEvent;

        return $this;
    }

    public function __toString()
    {
        $text = $this->getAuthor();
        if ($this->description != '')
            $text = $text." | ".  substr($this->description, 0, 10);
        return $text;

    }

    function getUploadDir()
    {
        return 'posters';
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

    public function getDate(): ?\DateTimeInterface
    {

        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSearchableText(): ?string
    {
        $this->buildSearchableText();
        return $this->searchableText;
    }

    public function setSearchableText(?string $searchableText): self
    {
        $this->searchableText = $searchableText;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function buildSearchableText(){

        $text = $this->author;

        if ($this->date != null)
            $text .= ' '.$this->date->format('Y');
        if ($this->associateEvent)
            $text .= ' '.$this->associateEvent->getName();

        $text = mb_strtolower($text, 'UTF-8');

        $replaceThis = Array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            '(' => '',
            ')' => ''
        );

        str_replace(array_keys($replaceThis), $replaceThis, $text);

        $this->searchableText = $text;

    }





    private $tempImageDetail1;

    private $tempImageDetail2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $pathImageDetail1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $pathImageDetail2;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileImageDetail1;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileImageDetail2;

    private $wideDimensions_ImageDetail;
    private $filenames_ImageDetail1;
    private $filenames_ImageDetail2;

    public function loadData_ImageDetail1()
    {
        /*This array need to be sortes ascendent*/
        $this->wideDimensions_ImageDetail = [20, 500, 800, 1200, 1900, 3600];

        $this->filenames_ImageDetail1 = [];
        foreach ($this->wideDimensions_ImageDetail as $wide)
            array_push($this->filenames_ImageDetail1, $this->getAbsolutePath_ImageDetail1().'-'.$wide.'.jpg');
    }

    public function loadData_ImageDetail2()
    {
        /*This array need to be sortes ascendent*/
        $this->wideDimensions_ImageDetail = [20, 500, 800, 1200, 1900, 3600];

        $this->filenames_ImageDetail2 = [];
        foreach ($this->wideDimensions_ImageDetail as $wide)
            array_push($this->filenames_ImageDetail2, $this->getAbsolutePath_ImageDetail2().'-'.$wide.'.jpg');
    }

    public function setFileImageDetail1(UploadedFile $file = null)
    {
        $this->fileImageDetail1 = $file;
        // check if we have an old image path
        if (isset($this->pathImageDetail1)) {
            // store the old name to delete after the update
            $this->tempImageDetail1 = $this->pathImageDetail1;
            $this->pathImageDetail1 = null;
        } else {
            $this->pathImageDetail1 = 'initial';
        }

        return $this;
    }

    public function setFileImageDetail2(UploadedFile $file = null)
    {
        $this->fileImageDetail2 = $file;
        // check if we have an old image path
        if (isset($this->pathImageDetail2)) {
            // store the old name to delete after the update
            $this->tempImageDetail2 = $this->pathImageDetail2;
            $this->pathImageDetail2 = null;
        } else {
            $this->pathImageDetail2 = 'initial2';
        }

        return $this;
    }

    public function getFileImageDetail1()
    {
        return $this->fileImageDetail1;
    }

    public function getFileImageDetail2()
    {
        return $this->fileImageDetail2;
    }


    public function getAbsolutePath_ImageDetail1()
    {
        return null === $this->pathImageDetail1
            ? null
            : $this->getUploadRootDir_ImageDetails().'/'.$this->pathImageDetail1;
    }

    public function getAbsolutePath_ImageDetail2()
    {
        return null === $this->pathImageDetail2
            ? null
            : $this->getUploadRootDir_ImageDetails().$this->pathImageDetail2;
    }

    //ruta de acceso web
    public function getWebPathImageDetail1($size = 500)
    {
        $prefixPath = 'uploads/images/posters/details/';
        $path = $prefixPath.$this->pathImageDetail1;

        if ($this->pathImageDetail1 == null)
            return null;

        if ($size == null) {
            if ($this->pathImageDetail1 == null)
                return null;
            return $path;
        }

        $this->loadData_ImageDetail1();

        for ($i = 0; count($this->wideDimensions_ImageDetail); $i++)
            if($this->wideDimensions_ImageDetail[$i]==$size)
                return $path.'-'.$size.'.jpg';

        return null;
    }

    //ruta de acceso web
    public function getWebPathImageDetail2($size = 500)
    {
        $prefixPath = 'uploads/images/posters/details/';
        $path = $prefixPath.$this->pathImageDetail2;

        if ($this->pathImageDetail2 == null)
            return null;

        if ($size == null)
            return $path;

        $this->loadData_ImageDetail2();

        for ($i = 0; count($this->wideDimensions_ImageDetail); $i++)
            if ($this->wideDimensions_ImageDetail[$i] == $size)
                return $path . '-' . $size . '.jpg';

        return null;
    }

    public function getOriginalImageWidthImageDetails1(){
        if ($this->getAbsolutePath_ImageDetail1() == null)
            return 0;

        $data = getimagesize($this->getAbsolutePath_ImageDetail1());
        $width = $data[0];
        return $width;
    }

    public function getOriginalImageWidthImageDetails2(){
        if ($this->getAbsolutePath_ImageDetail1() == null)
            return 0;

        $data = getimagesize($this->getAbsolutePath_ImageDetail2());
        $width = $data[0];
        return $width;
    }

    public function getConstraintWideDimensionsImageDetails1(){

        if ($this->getAbsolutePath_ImageDetail1() == null)
            return [0, 0];

        $this->loadData_ImageDetail1();
        $dimensions = array();
        foreach ($this->wideDimensions_ImageDetail as $dimension)
            if ($this->getOriginalImageWidthImageDetails1() >= $dimension)
                array_push($dimensions, $dimension);

        return $dimensions;
    }

    public function getConstraintWideDimensionsImageDetails2(){

        if ($this->getAbsolutePath_ImageDetail2() == null)
            return "kj";

        $this->loadData_ImageDetail2();
        $dimensions = array();
        foreach ($this->wideDimensions_ImageDetail as $dimension)
            if ($this->getOriginalImageWidthImageDetails2() >= $dimension)
                array_push($dimensions, $dimension);

        return $dimensions;
    }

    protected function getUploadRootDir_ImageDetails()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return  __DIR__ . '/../../public/uploads/images/posters/details/';
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFileImageDetail1()
    {
        if (null === $this->getFileImageDetail1()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error

        $this->getFileImageDetail1()->move($this->getUploadRootDir_ImageDetails(), $this->pathImageDetail1);

        // check if we have an old file
        if (isset($this->tempImageDetail1)) {
            // delete the old image
            @unlink($this->getUploadRootDir_ImageDetails().'/'.$this->tempImageDetail1);
            // clear the temp image path
            $this->tempImageDetail1 = null;
        }
        $this->updateThumbsImageDetail1();
        $this->fileImageDetail1 = null;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFileImageDetail2()
    {
        if (null === $this->getFileImageDetail2()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error

        $this->getFileImageDetail2()->move($this->getUploadRootDir_ImageDetails(), $this->pathImageDetail2);

        // check if we have an old file
        if (isset($this->tempImageDetail2)) {
            // delete the old image
            @unlink($this->getUploadRootDir_ImageDetails().'/'.$this->tempImageDetail2);
            // clear the temp image path
            $this->tempImageDetail2 = null;
        }

        $this->updateThumbsImageDetail2();
        $this->fileImageDetail2 = null;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadImageDetail1()
    {
        if (null !== $this->getFileImageDetail1()) {
            // haz lo que quieras para generar un nombre único
            $filename = substr(sha1(uniqid(mt_rand(), true)),0,10);
            $this->pathImageDetail1 = $filename.'.'.$this->getFileImageDetail1()->guessExtension();
        }

    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadImageDetail2()
    {
        if (null !== $this->getFileImageDetail2()) {
            // haz lo que quieras para generar un nombre único
            $filename = substr(sha1(uniqid(mt_rand(), true)),0,10);
            $this->pathImageDetail2 = $filename.'.'.$this->getFileImageDetail2()->guessExtension();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadImageDetail1()
    {
        if ($file = $this->getAbsolutePath_ImageDetail1()) {
            @unlink($file);
            $this->removeThumbs_ImageDetail1();
        }
    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUploadImageDetail2()
    {
        if ($file = $this->getAbsolutePath_ImageDetail2()) {
            @unlink($file);
            $this->removeThumbs_ImageDetail2();
        }
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPathImageDetail1($path)
    {
        $this->pathImageDetail1 = $path;

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPathImageDetail2($path)
    {
        $this->pathImageDetail2 = $path;

        return $this;
    }

    private function createThumb_ImageDetail1()
    {
        $this->loadData_ImageDetail1();
        $image = null;
        if(strpos($this->getAbsolutePath_ImageDetail1(),".png"))
            $image = @imagecreatefrompng($this->getAbsolutePath_ImageDetail1());
        else
            $image = @imagecreatefromjpeg($this->getAbsolutePath_ImageDetail1());

        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $height / $width;
        for($i = 0; $i<count($this->wideDimensions_ImageDetail); $i++)
        {
            $thumb_width = $this->wideDimensions_ImageDetail[$i];
            $thumb_height = $original_aspect*$thumb_width;

            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

            imagecopyresampled($thumb,
                $image,
                0, 0,
                0, 0,
                $thumb_width, $thumb_height,
                $width, $height);

            imageinterlace($thumb, true);
            @imagejpeg($thumb, $this->filenames_ImageDetail1[$i], 80);

        }
    }

    private function createThumb_ImageDetail2()
    {
        $this->loadData_ImageDetail2();
        $image = null;
        if(strpos($this->getAbsolutePath_ImageDetail2(),".png"))
            $image = @imagecreatefrompng($this->getAbsolutePath_ImageDetail2());
        else
            $image = @imagecreatefromjpeg($this->getAbsolutePath_ImageDetail2());


        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $height / $width;
        for($i = 0; $i<count($this->wideDimensions_ImageDetail); $i++)
        {
            $thumb_width = $this->wideDimensions_ImageDetail[$i];
            $thumb_height = $original_aspect*$thumb_width;


            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

            imagecopyresampled($thumb,
                $image,
                0, 0,
                0, 0,
                $thumb_width, $thumb_height,
                $width, $height);

            imageinterlace($thumb, true);
            imagejpeg($thumb, $this->filenames_ImageDetail2[$i], 80);
        }

    }

    public function removeThumbs_ImageDetail1()
    {
        $this->loadData_ImageDetail1();
        if($file = $this->getAbsolutePath_ImageDetail1())
        {
            foreach ($this->filenames_ImageDetail1 as $filename)
                @unlink($filename);
        }
    }

    public function removeThumbs_ImageDetail2()
    {
        $this->loadData_ImageDetail2();
        if($file = $this->getAbsolutePath_ImageDetail2())
        {
            foreach ($this->filenames_ImageDetail2 as $filename) {
                dump($filename);
                @unlink($filename);
            }



        }
    }

    public function updateThumbsImageDetail1(){
        $this->removeThumbs_ImageDetail1();
        $this->createThumb_ImageDetail1();
    }

    public function updateThumbsImageDetail2(){
        $this->removeThumbs_ImageDetail2();
        $this->createThumb_ImageDetail2();
    }

}
