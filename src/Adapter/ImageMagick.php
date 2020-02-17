<?php

namespace Rvdlee\ZfImageResizer\Adapter;

use Rvdlee\ZfImageResizer\Model\Image;

class ImageMagick extends AbstractImageResizer
{
    /**
     * @var string
     */
    protected $binaryPath = 'convert';

    public function resizeCommand(Image $image, bool $returnBase64 = false): string
    {
        return sprintf(
            'convert %s -quality 100 -resize %dx%d %s',
            $image->getPath(),
            $image->getTargetWidth(),
            $image->getTargetHeight(),
            $image->getTargetPath()
        );
    }

    public function cropCommand(Image $image, string $mode = Image::MANUAL_CROP, int $x = 0, int $y = 0): string
    {
        return sprintf(
            'convert %s -crop %dx%d+%d+%d %s',
            $image->getPath(),
            $image->getTargetWidth(),
            $image->getTargetHeight(),
            ($mode === Image::MANUAL_CROP ? $x : $image->getCropPositionX()),
            ($mode === Image::MANUAL_CROP ? $y : $image->getCropPositionY()),
            $image->getTargetPath()
        );
    }
}