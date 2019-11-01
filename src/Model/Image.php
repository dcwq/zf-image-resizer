<?php

namespace Rvdlee\ZfImageResizer\Model;

use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use SplFileInfo;

class Image
{
    const ONLY_RESIZE_MODUS     = 'resize';
    const RESIZE_AND_CROP_MODUS = 'resize-and-crop';
    const ONLY_CROP_MODUS       = 'crop';
    const MODUS                 = [
        self::ONLY_RESIZE_MODUS,
        self::RESIZE_AND_CROP_MODUS,
        self::ONLY_CROP_MODUS,
    ];

    const MANUAL_CROP       = 'manual';
    const UPPER_LEFT_CROP   = 'upper-left';
    const UPPER_MIDDLE_CROP = 'upper-middle';
    const UPPER_RIGHT_CROP  = 'upper-right';
    const MIDDLE_LEFT_CROP  = 'middle-left';
    const CENTERED_CROP     = 'centered';
    const MIDDLE_RIGHT_CROP = 'middle-right';
    const BOTTOM_LEFT       = 'bottom-left';
    const BOTTOM_MIDDLE     = 'bottom-middle';
    const BOTTOM_RIGHT      = 'bottom-right';
    const CROP_MODUS        = [
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
    protected $originalWidth;

    /**
     * @var int
     */
    protected $originalHeight;

    /**
     * @var int
     */
    protected $targetWidth = 0;

    /**
     * @var int
     */
    protected $targetHeight = 0;

    /**
     * @var int
     */
    protected $cropPositionX = 0;

    /**
     * @var int
     */
    protected $cropPositionY = 0;

    /**
     * Image constructor.
     *
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The provided file(%s) does not exist.', $path));
        }

        list($width, $height) = getimagesize($this->getPath());

        $this->setPath($path)
             ->setSplFileInfo(new SplFileInfo($path))
             ->setOriginalWidth($width)
             ->setOriginalHeight($height)
             ->calculateCropPositions(self::CENTERED_CROP);
    }

    /**
     * @param string $cropMode
     * @param int    $cropPositionX
     * @param int    $cropPositionY
     * @throws InvalidArgumentException
     */
    public function calculateCropPositions(
        string $cropMode = self::CENTERED_CROP,
        int $cropPositionX = 0,
        int $cropPositionY = 0
    ) {
        if (!in_array($cropMode, self::CROP_MODUS)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Crop mode \'%s\' is not a valid mode. Choose one of the following %s',
                    $cropMode,
                    implode(self::CROP_MODUS)
                )
            );
        }

        // These need to be set with actual values
        if ($this->getTargetWidth() === 0 || $this->getTargetHeight() === 0) {
            throw new InvalidArgumentException('TargetWidth and TargetHeight cannot be 0.');
        }

        // Calculate ratio to make accurate crop positions
        $ratio = $this->getOriginalWidth() / $this->getOriginalHeight();

        switch ($cropMode) {
            case self::UPPER_LEFT_CROP:
                $cropPositionX = 0;
                $cropPositionY = 0;
            break;
            case self::UPPER_MIDDLE_CROP:
                $cropPositionX = ($this->getOriginalWidth()) - ($this->getTargetWidth() / 2)z;
                $cropPositionY = 0;
            break;
            case self::UPPER_RIGHT_CROP:
                $cropPositionX = 0;
                $cropPositionY = 0;
            break;
        }
    }


    public function calculateResize()
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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return Image
     */
    public function setPath(string $path): Image
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo(): SplFileInfo
    {
        return $this->splFileInfo;
    }

    /**
     * @param SplFileInfo $splFileInfo
     *
     * @return Image
     */
    public function setSplFileInfo(SplFileInfo $splFileInfo): Image
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
    public function getOriginalWidth(): int
    {
        return $this->originalWidth;
    }

    /**
     * @param int $originalWidth
     * @return Image
     */
    public function setOriginalWidth(int $originalWidth): Image
    {
        $this->originalWidth = $originalWidth;
        return $this;
    }

    /**
     * @return int
     */
    public function getOriginalHeight(): int
    {
        return $this->originalHeight;
    }

    /**
     * @param int $originalHeight
     * @return Image
     */
    public function setOriginalHeight(int $originalHeight): Image
    {
        $this->originalHeight = $originalHeight;
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