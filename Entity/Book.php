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
 * Book
 *
 * @Vich\Uploadable
 */
class Book
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
    private $author;

    /**
     * @var string
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
     * @ImageAnnotation\ImageHandle(action="resize", width=100, height=100)
     */
    private $cover;

    /**
     * @var string
     */
    private $excerpt;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;


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
     * @return Book
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
     * Set author
     *
     * @param string $author
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Book
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
     * @return Book
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
     * @return Book
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
     * @return Book
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
     * @return Book
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
     * Set excerpt
     *
     * @param string $excerpt
     * @return Book
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Book
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
     * @return Book
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
