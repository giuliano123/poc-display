<?php

namespace DisplayBundle\EventListener;

use DisplayBundle\Entity\Place;
use DisplayBundle\Uploader\FileUploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PlaceUploadListener implements EventSubscriber
{
    private $uploader;
    private $fileName;

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

    public function prePersist(Place $place, LifecycleEventArgs $args)
    {
        $this->uploadFile($place);
    }

    public function preUpdate(Place $place, PreUpdateEventArgs $args)
    {
        // delete the old image if a new one is uploaded
        if ($args->getNewValue('image') instanceof UploadedFile && null !== $args->getOldValue('image')){
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->uploader->getTargetDir().'/'.$args->getOldValue('image'));
        }

        $this->uploadFile($place);
    }

    protected function uploadFile(Place $place)
    {
        $file = $place->getImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $this->fileName = $this->uploader->upload($file);
        }

        $place->setImage($this->fileName);
    }

    public function postLoad(Place $place, LifecycleEventArgs $args)
    {
        if ($this->fileName = $place->getImage()) {
            $place->setImage(new File($this->uploader->getTargetDir().'/'.$this->fileName));
        }
    }

    public function postRemove(Place $place, LifecycleEventArgs $args)
    {
        $file = $place->getImage();

        if (null !== $file) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($file->getPathName());
        }
    }
}