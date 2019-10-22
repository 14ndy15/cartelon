<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 * @ORM\HasLifecycleCallbacks
 */
abstract class ImageField
{
    private $temp;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    /**
     * @ORM\Column(type="string", length=255, )
     */
    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $wide_dimensions;

    private $filenames;

    public function getWideDimensions(){
        $this->loadData();
        return $this->wide_dimensions;
    }

    public function getConstraintWideDimensions(){
        $this->loadData();
        $dimensions = array();
        foreach ($this->wide_dimensions as $dimension)
            if ($this->getOriginalImageWidth() >= $dimension)
                array_push($dimensions, $dimension);

        return $dimensions;
    }

    public function loadData()
    {
        /*This array need to be sortes ascendent*/
        $this->wide_dimensions = [20, 500, 800, 1200, 1900, 3600];
        $this->filenames = [];
        foreach ($this->wide_dimensions as $wide)
            array_push($this->filenames, $this->getAbsolutePath().'-'.$wide.'.jpg');
    }


    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath($size = 500)
    {

        $prefixPath = 'uploads/images/';
        $path = $prefixPath.$this->getUploadDir().'/'.$this->path;

        if ($size == null)
            return $path;

        $this->loadData();

        if ($size == null)
            return $path;

        for ($i = 0; count($this->wide_dimensions); $i++)
            if($this->wide_dimensions[$i]==$size)
                return $path.'-'.$size.'.jpg';

    }

    public function getFullImageWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getOriginalImageWidth(){
        $data = getimagesize($this->getAbsolutePath());
        $width = $data[0];
        return $width;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return  __DIR__ . '/../../public/uploads/images/' .$this->getUploadDir();
    }

    abstract function getUploadDir();

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);
        $this->createThumb();

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            @unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = substr(sha1(uniqid(mt_rand(), true)),0,10);
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
            $this->removeThumbs();
        }
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Imagen
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    private function createThumb()
    {
        $this->loadData();
        $image = null;
        if(strpos($this->getAbsolutePath(),".png"))
            $image = @imagecreatefrompng($this->getAbsolutePath());
        else
            $image = @imagecreatefromjpeg($this->getAbsolutePath());

        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $height / $width;
        for($i = 0; $i<count($this->wide_dimensions); $i++)
        {
            $thumb_width = $this->wide_dimensions[$i];

            if ($width < $thumb_width)
                continue;

            $thumb_height = $original_aspect*$thumb_width;

            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

            imagecopyresampled($thumb,
                $image,
                0, 0,
                0, 0,
                $thumb_width, $thumb_height,
                $width, $height);

            imageinterlace($thumb, true);
            @imagejpeg($thumb, $this->filenames[$i], 85);

        }
    }

    public function removeThumbs()
    {
        $this->loadData();
        if($file = $this->getAbsolutePath())
        {
            foreach ($this->filenames as $filename)
                @unlink($filename);
        }
    }

    public function updateThumbs(){
        $this->removeThumbs();
        $this->createThumb();
    }
}
