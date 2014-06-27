<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Fulgurio\ImageHandlerBundle\Annotation as ImageAnnotation;

/**
 * MusicAlbum
 *
 * @Vich\Uploadable
 */
class MusicAlbum
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $media_type;

    /**
     * @var string
     */
    private $artist;

    /**
     * @var string
     * @Assert\NotBlank(message="validator.blank.title")
     */
    private $title;

    /**
     * @var string
     */
    private $ean;

    /**
     * @var integer
     */
    private $publication_year;

    /**
     * @var string
     */
    private $publisher;

    /**
     * @var string
     *
     * @ImageAnnotation\ImageHandle(action="crop", width=100, height=100)
     */
    private $cover;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tracks;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tracks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set media_type
     *
     * @param integer $mediaType
     * @return MusicAlbum
     */
    public function setMediaType($mediaType)
    {
        $this->media_type = $mediaType;

        return $this;
    }

    /**
     * Get media_type
     *
     * @return integer
     */
    public function getMediaType()
    {
        return $this->media_type;
    }

    /**
     * Set artist
     *
     * @param string $artist
     * @return MusicAlbum
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MusicAlbum
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ean
     *
     * @param string $ean
     * @return MusicAlbum
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set publication_year
     *
     * @param integer $publicationYear
     * @return MusicAlbum
     */
    public function setPublicationYear($publicationYear)
    {
        $this->publication_year = $publicationYear;

        return $this;
    }

    /**
     * Get publication_year
     *
     * @return integer
     */
    public function getPublicationYear()
    {
        return $this->publication_year;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     * @return MusicAlbum
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return MusicAlbum
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return MusicAlbum
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
            $this->created_at = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTime();
    }


    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     * @Vich\UploadableField(mapping="cover_image", fileNameProperty="cover")
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     */
    private $coverFile;

    /**
     * @var string
     */
    private $coverUrl;

    /**
     * Set coverFile
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|\Symfony\Component\HttpFoundation\File\File $coverFile
     */
    public function setCoverFile($coverFile)
    {
        $this->coverFile = $coverFile;
        // Because we need an update of entity object when form submit a file,
        // we make a fake update of $cover
        if ($coverFile instanceof UploadedFile && $coverFile)
        {
           $this->setCover(time());
        }

        return $this;
    }

    /**
     * Get coverFile
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getCoverFile()
    {
        return $this->coverFile;
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
     * Add tracks
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks
     * @return MusicAlbum
     */
    public function addTrack(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks)
    {
        $this->tracks[] = $tracks;

        return $this;
    }

    /**
     * Remove tracks
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks
     */
    public function removeTrack(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks)
    {
        $this->tracks->removeElement($tracks);
    }

    /**
     * Get tracks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->publication_year != NULL
            && ($this->publication_year < 1900 || $this->publication_year > date('Y') + 1))
        {
            $context->addViolationAt(
                    'publication_year',
                    'validator.invalid.publication_year',
                    array(),
                    'music'
                    );
        }
    }
}
