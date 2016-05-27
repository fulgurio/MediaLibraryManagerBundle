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

/**
 * MusicTrack
 *
 * @ORM\Table(name="music_track")
 * @ORM\Entity()
 */
class MusicTrack
{
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
     * @ORM\Column(name="volume_number", type="smallint", nullable=false)
     */
    private $volumeNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="track_number", type="smallint", nullable=false)
     */
    private $trackNumber;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="smallint", nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="lyrics", type="text", nullable=true)
     */
    private $lyrics;

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
     * @var \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum
     *
     * @ORM\ManyToOne(targetEntity="Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum", inversedBy="tracks")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $musicAlbum;

    /***************************************************************************
     *                             GENERATED CODE                              *
     **************************************************************************/

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
     * Set volumeNumber
     *
     * @param integer $volumeNumber
     * @return MusicTrack
     */
    public function setVolumeNumber($volumeNumber)
    {
        $this->volumeNumber = $volumeNumber;

        return $this;
    }

    /**
     * Get volumeNumber
     *
     * @return integer
     */
    public function getVolumeNumber()
    {
        return $this->volumeNumber;
    }

    /**
     * Set trackNumber
     *
     * @param integer $trackNumber
     * @return MusicTrack
     */
    public function setTrackNumber($trackNumber)
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    /**
     * Get trackNumber
     *
     * @return integer
     */
    public function getTrackNumber()
    {
        return $this->trackNumber;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MusicTrack
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
     * Set duration
     *
     * @param integer $duration
     * @return MusicTrack
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set lyrics
     *
     * @param string $lyrics
     * @return MusicTrack
     */
    public function setLyrics($lyrics)
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    /**
     * Get lyrics
     *
     * @return string
     */
    public function getLyrics()
    {
        return $this->lyrics;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MusicTrack
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
     * @return MusicTrack
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
     * Set musicAlbum
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum $musicAlbum
     * @return MusicTrack
     */
    public function setMusicAlbum(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum $musicAlbum)
    {
        $this->musicAlbum = $musicAlbum;

        return $this;
    }

    /**
     * Get musicAlbum
     *
     * @return \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum
     */
    public function getMusicAlbum()
    {
        return $this->musicAlbum;
    }
}
