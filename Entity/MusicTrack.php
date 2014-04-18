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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MusicTrack
 */
class MusicTrack
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $volume_number;

    /**
     * @var integer
     */
    private $track_number;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var string
     */
    private $lyrics;

    /**
     * @var \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum
     */
    private $music_album;


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
     * Set volume_number
     *
     * @param integer $volumeNumber
     * @return MusicTrack
     */
    public function setVolumeNumber($volumeNumber)
    {
        $this->volume_number = $volumeNumber;

        return $this;
    }

    /**
     * Get volume_number
     *
     * @return integer
     */
    public function getVolumeNumber()
    {
        return $this->volume_number;
    }

    /**
     * Set track_number
     *
     * @param integer $trackNumber
     * @return MusicTrack
     */
    public function setTrackNumber($trackNumber)
    {
        $this->track_number = $trackNumber;

        return $this;
    }

    /**
     * Get track_number
     *
     * @return integer
     */
    public function getTrackNumber()
    {
        return $this->track_number;
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
     * Set music_album
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum $musicAlbum
     * @return MusicTrack
     */
    public function setMusicAlbum(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum $musicAlbum = null)
    {
        $this->music_album = $musicAlbum;

        return $this;
    }

    /**
     * Get music_album
     *
     * @return \Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum
     */
    public function getMusicAlbum()
    {
        return $this->music_album;
    }
}
