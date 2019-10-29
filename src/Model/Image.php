<?php

namespace Rvdlee\ZfImageResizer\Model;

use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use SplFileInfo;

class Image
{
    const ONLY_RESIZE_MODUS = 'resize';
    const RESIZE_AND_CROP_MODUS = 'resize-and-crop';
    const ONLY_CROP_MODUS = 'crop';
    const MODUS = [
        self::ONLY_RESIZE_MODUS,
        self::RESIZE_AND_CROP_MODUS,
        self::ONLY_CROP_MODUS,
    ];

    const MANUAL_CROP = 'manual';
    const UPPER_LEFT_CROP = 'upper-left';
    const UPPER_MIDDLE_CROP = 'upper-middle';
    const UPPER_RIGHT_CROP = 'upper-right';
    const MIDDLE_LEFT_CROP = 'middle-left';
    const CENTERED_CROP = 'centered';
    const MIDDLE_RIGHT_CROP = 'middle-right';
    const BOTTOM_LEFT = 'bottom-left';
    const BOTTOM_MIDDLE = 'bottom-middle';
    const BOTTOM_RIGHT = 'bottom-right';
    const CROP_MODUS = [
        self::MANUAL_CROP,
        self::UPPER_LEFT_CROP,
        self::UPPER_MIDDLE_CROP,
        self::UPPER_RIGHT_CROP,
        self::MIDDLE_LEFT_CROP,
        self::CENTERED_CROP,
        self::MIDDLE_RIGHT_CROP,
        self::BOTTOM_LEFT,
        self::BOTTOM_MIDDLE,
        self::BOTTOM_RIGHT,
    ];

    /**
     * @var string
     */
    protected $path;

    /**
     * @var SplFileInfo
     */
    protected $splFileInfo;

    /**
     * @var int
     */
    protected $targetWidth;

    /**
     * @var int
     */
    protected $targetHeight;

    /**
     * @var int
     */
    protected $cropPositionX;

    /**
     * @var int
     */
    protected $cropPositionY;

    /**
     * Image constructor.
     *
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if ( ! file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The provided file(%s) does not exist.', $path));
        }

        $this->setPath($path)
             ->setSplFileInfo(new SplFileInfo($path));
    }

    public function calculateCropPositions()
    {

    }

    /**
     * Check if the parameters have been set to resize or crop the image.
     */
    public function validateImage()
    {

    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return Image
     */
    public function setPath(string $path) : Image
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo() : SplFileInfo
    {
        return $this->splFileInfo;
    }

    /**
     * @param SplFileInfo $splFileInfo
     *
     * @return Image
     */
    public function setSplFileInfo(SplFileInfo $splFileInfo) : Image
    {
        $this->splFileInfo = $splFileInfo;

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetWidth(): int
    {
        return $this->targetWidth;
    }

    /**
     * @param int $targetWidth
     * @return Image
     */
    public function setTargetWidth(int $targetWidth): Image
    {
        $this->targetWidth = $targetWidth;
        return $this;
    }

    /**
     * @return int
     */
    public function getTargetHeight(): int
    {
        return $this->targetHeight;
    }

    /**
     * @param int $targetHeight
     * @return Image
     */
    public function setTargetHeight(int $targetHeight): Image
    {
        $this->targetHeight = $targetHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getCropPositionX(): int
    {
        return $this->cropPositionX;
    }

    /**
     * @param int $cropPositionX
     * @return Image
     */
    public function setCropPositionX(int $cropPositionX): Image
    {
        $this->cropPositionX = $cropPositionX;
        return $this;
    }

    /**
     * @return int
     */
    public function getCropPositionY(): int
    {
        return $this->cropPositionY;
    }

    /**
     * @param int $cropPositionY
     * @return Image
     */
    public function setCropPositionY(int $cropPositionY): Image
    {
        $this->cropPositionY = $cropPositionY;
        return $this;
    }
}