<?php

namespace DisplayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="DisplayBundle\Repository\EventRepository")
 * @ORM\EntityListeners({"DisplayBundle\EventListener\EventUploadListener"})
 */
class Event
{
    const MAX_PUBLICATION_DATE_END = '2037-12-31 23:59:59';

    public function __construct()
    {
        $this->publicationEndDate = new \DateTime(self::MAX_PUBLICATION_DATE_END);
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="event_date", type="string", length=255)
     */
    private $eventDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publication_date", type="datetime")
     */
    private $publicationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publication_end_date", type="datetime")
     */
    private $publicationEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="DisplayBundle\Entity\Place", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $place;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255)
     */
    private $poster;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace(Place $place = null)
    {
        $this->place = $place;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Event
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
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Event
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set eventDate
     *
     * @param string $eventDate
     *
     * @return Event
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return string
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     *
     * @return Event
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * Set publicationEndDate
     *
     * @param \DateTime $publicationEndDate
     *
     * @return Event
     */
    public function setPublicationEndDate($publicationEndDate)
    {
        $this->publicationEndDate = $publicationEndDate;

        return $this;
    }

    /**
     * Get publicationEndDate
     *
     * @return \DateTime
     */
    public function getPublicationEndDate()
    {
        return $this->publicationEndDate;
    }

    /**
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return null|string
     */
    public function getPosterPath()
    {
        if (null === $this->getPoster()) {
            return null;
        }

        return 'uploads/event/'.$this->getPoster()->getFileName();
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return null|string
     */
    public function getPicturePath()
    {
        if (null === $this->getPicture()) {
            return null;
        }

        return $this->getTargetDir() . $this->getPicture()->getFileName();
    }

    public function getTargetDir()
    {
        return 'uploads/event/';
    }

}

