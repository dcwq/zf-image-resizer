<?php

namespace Rvdlee\ZfImageResizer\Interfaces;

use Rvdlee\ZfImageResizer\Model\Image;

interface ImageResizerInterface
{
    /**
     * This will resize the image
     *
     * @param Image $image
     * @param bool  $returnBase64
     * @return string
     */
    public function resizeCommand(Image $image, bool $returnBase64): string;

    /**
     * This will crop the image. Refer to the Image class for automatic crop modus.
     *
     * @param Image  $image
     * @param string $mode
     * @param int    $x
     * @param int    $y
     * @return string
     */
    public function cropCommand(Image $image, string $mode = Image::MANUAL_CROP, int $x = 0, int $y = 0): string;

    /**
     * This function will run the validation chain to verify
     * if the image is fit to resize through adapter.
     *
     * @param string $imagePath
     *
     * @return bool
     */
    public function canHandle(string $imagePath): bool;
}