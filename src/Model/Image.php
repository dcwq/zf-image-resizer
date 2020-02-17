<?php

namespace Rvdlee\ZfImageResizer\Model;

use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use SplFileInfo;

class Image
{
    const ONLY_RESIZE_MODUS     = 'resize';
    const CROP_AND_RESIZE_MODUS = 'crop-and-resize';
    const ONLY_CROP_MODUS       = 'crop';
    const MODUS                 = [
        self::ONLY_RESIZE_MODUS,
        self::CROP_AND_RESIZE_MODUS,
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
     * @var string
     */
    protected $targetPath;

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
     * @param int    $targetWidth
     * @param int    $targetHeight
     * @param string $cropMode
     * @param int    $x
     * @param int    $y
     * @throws InvalidArgumentException
     */
    public function __construct(string $path, int $targetWidth, int $targetHeight, string $cropMode = self::CENTERED_CROP, int $x = 0, int $y = 0)
    {
        // Check if given path exists
        if ( ! file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The provided file(%s) does not exist.', $path));
        }

        // Get the original image dimensions and calculate ratio
        list($width, $height) = getimagesize($path);
        $ratio = ($width < $height ? $width : $height) / ($width > $height ? $width : $height);

        // If either of these is not set, scale to ratio
        if ($targetWidth === 0 || $targetHeight === 0) {
            if ($targetWidth === 0) {
                $targetWidth = $width * $ratio;
            }

            if ($targetHeight === 0) {
                $targetHeight = $height * $ratio;
            }
        }

        // Prep this model
        $this->setPath($path)
             ->setSplFileInfo(new SplFileInfo($path))
             ->setOriginalWidth($width)
             ->setOriginalHeight($height)
             ->setTargetWidth($targetWidth)
             ->setTargetHeight($targetHeight)
             ->calculateCropPositions($cropMode, $x, $y)
             ->setTargetPath($this->generateFileName());
    }

    /**
     * Generate a new unique name based on all the image parameters
     *
     * @return mixed
     */
    public function generateFileName()
    {
        // Extract the basename out of the path
        $filename = basename($this->getPath());
        // Get the filepath
        $explodedFilepath = explode(DIRECTORY_SEPARATOR, $this->getPath());
        // Reconstruct the base file path
        $baseFilepath = implode(DIRECTORY_SEPARATOR, array_slice($explodedFilepath, 0, count($explodedFilepath)-1));
        // Explode the filename
        $explodedFilename = explode('.', $filename);
        // Generate new name
        $thumbname = sprintf(
            '%s.%s',
            bin2hex(random_bytes(64)),
            end($explodedFilename)
        );

        return $baseFilepath . DIRECTORY_SEPARATOR . $thumbname;
    }


    /**
     * @param string $cropMode
     * @param int    $cropPositionX
     * @param int    $cropPositionY
     * @return Image
     * @throws InvalidArgumentException
     */
    public function calculateCropPositions(
        string $cropMode = self::CENTERED_CROP,
        int $cropPositionX = 0,
        int $cropPositionY = 0
    ): Image {
        // Check if we've recieved a valid crop modus
        if ( ! in_array($cropMode, self::CROP_MODUS)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Crop mode \'%s\' is not a valid mode. Choose one of the following %s',
                    $cropMode,
                    implode(self::CROP_MODUS)
                )
            );
        }

        // If we want to manually control the crop just set it and return this (fluent interface)
        if ($cropMode === self::MANUAL_CROP) {
            return $this->setCropPositionX($cropPositionX)->setCropPositionY($cropPositionY);
        }

        // These need to be set with actual values
        if ($this->getTargetWidth() === 0 || $this->getTargetHeight() === 0) {
            throw new InvalidArgumentException('TargetWidth and TargetHeight cannot be 0.');
        }

        // Do some automatic (lazy) crop calculations
        switch ($cropMode) {
            case self::UPPER_LEFT_CROP:
                $cropPositionX = 0;
                $cropPositionY = 0;
            break;
            case self::UPPER_MIDDLE_CROP:
                $cropPositionX = ($this->getOriginalWidth() / 2) - ($this->getTargetWidth() / 2);
                $cropPositionY = 0;
            break;
            case self::UPPER_RIGHT_CROP:
                $cropPositionX = $this->getOriginalWidth() - $this->getTargetWidth();
                $cropPositionY = 0;
            break;
            case self::MIDDLE_LEFT_CROP:
                $cropPositionX = 0;
                $cropPositionY = ($this->getOriginalHeight() / 2) - ($this->getTargetHeight() / 2);
            break;
            case self::CENTERED_CROP:
                $cropPositionX = ($this->getOriginalWidth() / 2) - ($this->getTargetWidth() / 2);
                $cropPositionY = ($this->getOriginalHeight() / 2) - ($this->getTargetHeight() / 2);
            break;
            case self::MIDDLE_RIGHT_CROP:
                $cropPositionX = $this->getOriginalWidth() - $this->getTargetWidth();
                $cropPositionY = ($this->getOriginalHeight() / 2) - ($this->getTargetHeight() / 2);
            break;
            case self::BOTTOM_LEFT:
                $cropPositionX = 0;
                $cropPositionY = $this->getOriginalHeight() - $this->getTargetHeight();
            break;
            case self::BOTTOM_MIDDLE:
                $cropPositionX = ($this->getOriginalWidth() / 2) - ($this->getTargetWidth() / 2);
                $cropPositionY = $this->getOriginalHeight() - $this->getTargetHeight();
            break;
            case self::BOTTOM_RIGHT:
                $cropPositionX = $this->getOriginalWidth() - $this->getTargetWidth();
                $cropPositionY = $this->getOriginalHeight() - $this->getTargetHeight();
            break;
        }

        // Set the calculated crop positions and return this (fluent interface)
        return $this->setCropPositionX($cropPositionX)->setCropPositionY($cropPositionY);
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
     * @return string
     */
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    /**
     * @param string $targetPath
     * @return Image
     */
    public function setTargetPath(string $targetPath): Image
    {
        $this->targetPath = $targetPath;
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