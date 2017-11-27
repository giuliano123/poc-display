<?php

namespace AdminBundle\Namer;


use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class VichDirectoryNamer implements DirectoryNamerInterface, ConfigurableInterface
{

    private $subdir;

    public function configure(array $options)
    {
        $options = array_merge(['subdir' => $this->subdir], $options);

        $this->subdir = $options['subdir'];
    }

    public function directoryName($object, PropertyMapping $mapping)
    {
        return $this->subdir;
    }
}