<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 * @ORM\HasLifecycleCallbacks
 */
abstract class FieldField
{
    private $tempFile;
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
    public $pathFile;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileFile;


    public function setFileFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->pathFile)) {
            // store the old name to delete after the update
            $this->temp = $this->pathFile;
            $this->pathFile = null;
        } else {
            $this->pathFile = 'initial';
        }

        return $this;
    }

    public function getFileFile()
    {
        return $this->fileFile;
    }

    public function getFileAbsolutePath()
    {
        return null === $this->pathFile
        ? null
        : $this->getUploadRootDir().'/'.$this->pathFile;
    }

    public function getFileWebPath()
    {
        $prefixPath = 'uploads/files/';
        $path = $prefixPath.$this->pathFile;

        return $path;
    }

    protected function getFileUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return  __DIR__ . '/../../public/uploads/files/';
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFile()
    {
        if (null === $this->getFileFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileFile()->move($this->getFileUploadRootDir(), $this->pathFile);

        // check if we have an old file
        if (isset($this->temp)) {
            // delete the old image
            @unlink($this->getFileUploadRootDir().'/'.$this->tempFile);
            // clear the temp image path
            $this->tempFile = null;
        }
        $this->fileFile = null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadFile()
    {
        if (null !== $this->getFileFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = substr(sha1(uniqid(mt_rand(), true)),0,10);
            $this->pathFile = $filename.'.'.$this->getFileFile()->guessExtension();
        }
    }

     /**
     * @ORM\PostRemove()
     */
     public function removeUploadFile()
     {
        if ($file = $this->getFileAbsolutePath()) {
            @unlink($file);
        }
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPathFile($path)
    {
        $this->pathFile = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPathFile()
    {
        return $this->pathFile;
    }

}
