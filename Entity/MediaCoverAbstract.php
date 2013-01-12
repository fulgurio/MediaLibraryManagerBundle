<?php

namespace Fulgurio\MediaLibraryManagerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * MusicAlbum
 */
abstract class MediaCoverAbstract
{
    /**
     * @var string
     */
    protected $cover;

    /**
     * @var integer
     */
    private $coverSize = 50;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;


    public function preUpload()
    {
        if (null !== $this->file)
        {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), TRUE));
            $this->cover = $filename . '.' . $this->file->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->file)
        {
            return;
        }
        $this->cropPicture($this->file->getPathname(), $this->getUploadRootDir() . '/' . $this->cover, $this->coverSize, $this->coverSize, 80);
        unset($this->file);
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath())
        {
            //@todo : add owner
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->cover ? null : $this->getUploadRootDir() . '/' . $this->cover;
    }

    public function getWebPath()
    {
        return null === $this->cover ? null : $this->getUploadDir() . '/' . $this->cover;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        //@todo : use owner id
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads';
    }

    /**
     * Image cropper
     *
     * @param string $sourcefile
     * @param string $destfile
     * @param integer $fw
     * @param integer $fh
     * @param integer $jpegquality
     * @return array New site of picture
     */
    private function cropPicture($sourcefile, $destfile, $fw, $fh, $jpegquality = 100)
    {
        list($ow, $oh, $from_type) = getimagesize($sourcefile);
        switch($from_type) {
            case 1: // GIF
                $srcImage = imageCreateFromGif($sourcefile) or die('Impossible de convertir cette image');
                break;
            case 2: // JPG
                $srcImage = imageCreateFromJpeg($sourcefile) or die('Impossible de convertir cette image');
                break;
            case 3: // PNG
                $srcImage = imageCreateFromPng($sourcefile) or die('Impossible de convertir cette image');
                break;
            default:
                return;
        }
        if (($fw / $ow) > ($fh / $oh)) {
            $tempw = $fw;
            $temph = ($fw / $ow) * $oh;
        }
        else {
            $tempw = ($fh / $oh) * $ow;
            $temph = $fh;
        }
        $tempImage = imageCreateTrueColor($fw, $fh);
        //    imageAntiAlias($tempImage, TRUE);
        imagecopyresampled($tempImage, $srcImage, ($fw - $tempw) / 2, ($fh - $temph) / 2, 0, 0, $tempw, $temph, $ow, $oh);
        imageJpeg($tempImage, $destfile, $jpegquality);
        return (getimagesize($destfile));
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return MusicAlbum
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set cover size
     *
     * @param integer $size
     */
    public function setCoverSize($size)
    {
        $this->coverSize = $size;
    }
}