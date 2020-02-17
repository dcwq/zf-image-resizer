<?php

namespace Rvdlee\ZfImageResizer\Interfaces;

use Rvdlee\ZfImageResizer\Model\Image;

interface Resizable
{
    /**
     * This function will return the properties that are resizable.
     *
     * @return array
     */
    public function getResizables(): array;

    /**
     * This function will return the sizes that are accepted by the resize service.
     *
     * @return array
     */
    public function getSizes(): array;
}