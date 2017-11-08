<?php

namespace DisplayBundle\EventListener;


use DisplayBundle\Entity\Event;
use DisplayBundle\Uploader\FileUploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EventUploadListener implements EventSubscriber
{
    private $uploader;
    private $posterName;
    private $pictureName;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
            Events::postRemove
        );
    }

    public function prePersist(Event $event, LifecycleEventArgs $args)
    {
        $this->uploadFile($event);
    }

    public function preUpdate(Event $event, PreUpdateEventArgs $args)
    {
        // delete the old image if a new one is uploaded
        if ($args->getNewValue('poster') instanceof UploadedFile && null !== $args->getOldValue('poster')){
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->uploader->getTargetDir().'/'.$args->getOldValue('poster'));
        }

        if ($args->getNewValue('picture') instanceof UploadedFile && null !== $args->getOldValue('picture')){
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->uploader->getTargetDir().'/'.$args->getOldValue('picture'));
        }

        $this->uploadFile($event);
    }

    protected function uploadFile(Event $event)
    {
        $poster = $event->getPoster();
        $picture = $event->getPicture();

        // only upload new files
        if ($poster instanceof UploadedFile) {
            $this->posterName = $this->uploader->upload($poster);
        }

        if ($picture instanceof UploadedFile) {
            $this->pictureName = $this->uploader->upload($picture);
        }

        $event->setPoster($this->posterName);
        $event->setPicture($this->pictureName);
    }

    public function postLoad(Event $event, LifecycleEventArgs $args)
    {
        if ($this->posterName = $event->getPoster()) {
            $event->getPoster(new File($this->uploader->getTargetDir().'/'.$this->posterName));
        }

        if ($this->pictureName = $event->getPicture()) {
            $event->getPicture(new File($this->uploader->getTargetDir().'/'.$this->pictureName));
        }
    }

    public function postRemove(Event $event, LifecycleEventArgs $args)
    {
        $poster = $event->getPoster();
        $picture = $event->getPicture();

        if (null !== $poster) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($poster->getPathName());
        }

        if (null !== $picture) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($picture->getPathName());
        }
    }
}