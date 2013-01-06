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

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->cover);

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
}