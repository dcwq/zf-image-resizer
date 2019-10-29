<?php

namespace Rvdlee\ZfImageResizer\Interfaces;

use Rvdlee\ZfImageResizer\Model\Image;

interface ImageResizerInterface
{
    /**
     * This will resize the image
     *
     * @param Image $image
     *
     * @return string
     */
    public function resizeCommand(Image $image) : string;

    /**
     * This function will run the validation chain to verify
     * if the image is fit to resize through adapter.
     *
     * @param string $imagePath
     *
     * @return bool
     */
    public function canHandle(string $imagePath) : bool;
}