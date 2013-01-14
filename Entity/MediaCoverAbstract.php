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
     * @var string
     */
    private $coverThumbnail;

    /**
     * @var string
     */
    private $coverUrl;

    /**
     * @var integer
     */
    private $coverSize = 50;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;


    /**
     * ORM\PrePersist
     */
    public function preUpload()
    {
        if (NULL !== $this->file)
        {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), TRUE));
            $this->cover = $filename . '.' . $this->file->guessExtension();
        }
        else if (NULL !== $this->coverUrl)
        {
            $filename = sha1(uniqid(mt_rand(), TRUE));
            $this->cover = $filename . strrchr($this->coverUrl, '.');
        }
    }

    /**
     * ORM\PostPersist
     */
    public function upload()
    {
        if (NULL === $this->file)
        {
            if ($this->coverUrl !== NULL)
            {
                //@todo: check if config allow url access
                $this->cropPicture($this->coverUrl, $this->getUploadRootDir() . '/' . $this->getThumbnail(), $this->coverSize, $this->coverSize, 80);
                copy($this->coverUrl, $this->getUploadRootDir() . '/' . $this->cover);
            }
            return;
        }
        $this->cropPicture($this->file->getPathname(), $this->getUploadRootDir() . '/' . $this->getThumbnail(), $this->coverSize, $this->coverSize, 80);
        $this->file->move($this->getUploadRootDir(), $this->cover);
        unset($this->file);
    }

    /**
     * ORM\PostRemove
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath())
        {
            //@todo : add owner
            unlink($file);
        }
    }

    /**
     * Get cover with absolut path
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->cover ? null : $this->getUploadRootDir() . '/' . $this->cover;
    }

    /**
     * Get cover for url
     *
     * @return string
     */
    public function getWebPath()
    {
        return null === $this->cover ? null : $this->getUploadDir() . '/' . $this->cover;
    }

    /**
     * Get thumbnail url
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
    	return NULL === $this->getThumbnail() ? NULL : $this->getUploadDir() . '/' . $this->getThumbnail();
    }

    /**
     * Get absolut path of cover directory
     *
     * @return string
     */
    private function getUploadRootDir()
    {
        //@todo : add owner
        if (is_dir(__DIR__ . '/../../../../web/'))
        {
            return __DIR__ . '/../../../../web/' . $this->getUploadDir();
        }
        else
        {
            return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
        }
    }

    /**
     * Name of the upload dir
     *
     * @return string
     */
    private function getUploadDir()
    {
        //@todo : use owner id
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
     * Set coverUrl
     *
     * @param string $coverUrl
     * @return MusicAlbum
     */
    public function setCoverUrl($coverUrl)
    {
    	$this->coverUrl = $coverUrl;

    	return $this;
    }

    /**
     * Get coverUrl
     *
     * @return string
     */
    public function getCoverUrl()
    {
    	return $this->coverUrl;
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

    /**
     * Get coverThumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        if (NULL === $this->coverThumbnail)
        {
            $extension = strrchr($this->cover, '.');
            $this->coverThumbnail = substr($this->cover, 0, -mb_strlen($extension)) . '_thumb' . $extension;
        }
        return ($this->coverThumbnail);
    }
}