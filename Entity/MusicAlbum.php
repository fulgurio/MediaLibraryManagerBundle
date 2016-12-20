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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * MusicAlbum
 *
 * @ORM\Table(name="music_album")
 * @ORM\Entity(repositoryClass="Fulgurio\MediaLibraryManagerBundle\Repository\MusicAlbumRepository")
 * @Vich\Uploadable
 */
class MusicAlbum
{
    /**
     * @var File $coverFile
     *
     * @Vich\UploadableField(mapping="music_cover", fileNameProperty="cover")
     */
    protected $coverFile;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setCoverFile(File $image = null)
    {
        $this->coverFile = $image;

        if ($image)
        {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getCoverFile()
    {
        return $this->coverFile;
    }

    /**
     * Validate entity data
     *
     * @param ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->publicationYear != null
            && ($this->publicationYear < 1900 || $this->publicationYear > date('Y') + 1))
        {
            $context->buildViolation('music.publication_year.invalid')
                    ->setTranslationDomain('music')
                    ->atPath('publication_year')
                    ->addViolation();
        }
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_type", type="smallint", nullable=false)
     */
    private $mediaType;

    /**
     * @var string
     *
     * @ORM\Column(name="artist", type="string", length=128, nullable=true)
     */
    private $artist;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "music.title.not_blank")
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\Isbn()
     * @ORM\Column(name="ean", type="string", length=13, nullable=true)
     */
    private $ean;

    /**
     * @var integer
     *
     * @ORM\Column(name="publication_year", type="smallint", nullable=true)
     */
    private $publicationYear;

    /**
     * @var string
     *
     * @ORM\Column(name="publisher", type="string", length=32, nullable=true)
     */
    private $publisher;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=64, nullable=true)
     */
    private $cover;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack", mappedBy="musicAlbum")
     */
    private $tracks;

    /***************************************************************************
     *                             GENERATED CODE                              *
     **************************************************************************/

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
     * Set mediaType
     *
     * @param integer $mediaType
     * @return MusicAlbum
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get mediaType
     *
     * @return integer
     */
    public function getMediaType()
    {
        return $this->mediaType;
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
     * Set publicationYear
     *
     * @param integer $publicationYear
     * @return MusicAlbum
     */
    public function setPublicationYear($publicationYear)
    {
        $this->publicationYear = $publicationYear;

        return $this;
    }

    /**
     * Get publicationYear
     *
     * @return integer
     */
    public function getPublicationYear()
    {
        return $this->publicationYear;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MusicAlbum
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return MusicAlbum
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
}
