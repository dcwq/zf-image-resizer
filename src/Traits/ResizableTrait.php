<?php

namespace Rvdlee\ZfImageResizer\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use GamersGalaxy\Entity\AbstractFile;
use Rvdlee\ZfImageResizer\Model\Image;

trait ResizableTrait
{
    /**
     * This will return all the objects from resizable properties
     *
     * @param null $property
     *
     * @return array
     */
    public function getResizableObjects($property = null) : array
    {
        $objects = [];

        foreach ($this::RESIZEABLES as $resizable) {
            if (!property_exists($this, $resizable)) {
                continue;
            }

            $collectionOrEntity = $this->{$resizable};

            if ($collectionOrEntity instanceof Collection) {
                $objects = array_merge($objects, $collectionOrEntity->getValues());
            }

            if ($collectionOrEntity instanceof AbstractFile) {
                $objects = array_merge($objects, [$collectionOrEntity]);
            }
        }

        return $objects;
    }

    /**
     * This function will return the properties that are resizable.
     *
     * @return array
     */
    public function getResizables(): array
    {
        return self::RESIZEABLES;
    }

    /**
     * This function will return the sizes that are accepted by the resize service.
     *
     * @return array
     */
    public function getSizes(): array
    {
        return self::SIZES;
    }
}