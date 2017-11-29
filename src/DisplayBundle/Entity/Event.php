<?php

namespace DisplayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="DisplayBundle\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
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
     * @Assert\NotBlank(message="Le champ 'titre' est obligatoire")
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
     * @ORM\Column(name="event_date", type="string", length=255, nullable = true)
     */
    private $eventDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publication_date", type="datetime")
     * @Assert\NotBlank(message="Le champ 'date de publication' est obligatoire")
     */
    private $publicationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publication_end_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $publicationEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="DisplayBundle\Entity\Place", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Vous devez choisir un lieu")
     */
    private $place;

    /**
     * @Vich\UploadableField(mapping="event_poster", fileNameProperty="poster")
     *
     * @var File
     */
    private $posterFile;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255)
     */
    private $poster;

    /**
     * @Vich\UploadableField(mapping="event_picture", fileNameProperty="picture")
     *
     * @var File
     */
    private $pictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $updatedAt;

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

    public function setPosterFile(File $image = null)
    {
        if ($image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        $this->posterFile = $image;
    }

    /**
     * @return File|null
     */
    public function getPosterFile()
    {
        return $this->posterFile;
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

    public function setPictureFile(File $image = null)
    {
        if ($image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        $this->pictureFile = $image;
    }

    /**
     * @return File|null
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
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
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

}

