<?php

namespace Rvdlee\ZfImageResizer\Traits;

use Rvdlee\ZfImageResizer\Model\Image;

trait ResizableTrait
{
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