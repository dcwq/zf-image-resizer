<?php

namespace Rvdlee\ZfImageResizer\Service;

use Exception;
use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use Rvdlee\ZfImageResizer\Interfaces\ImageResizerInterface;
use Rvdlee\ZfImageResizer\Model\Image;
use Zend\Log\LoggerInterface;

class ImageResizerService
{
    /**
     * @var ImageResizerInterface
     */
    protected $adapter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ImageResizerInterface $adapter
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ImageResizerInterface $adapter, LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->getLogger()->info('Constucting ImageResizerService');

        if (!$adapter instanceof ImageResizerInterface) {
            throw new InvalidArgumentException(
                sprintf('Adapter configured does not have the %s interface.', ImageResizerInterface::class)
            );
        }

        $this->setAdapter($adapter);
    }

    /**
     * @param string $imagePath
     * @param int    $newWidth
     * @param int    $newHeight
     * @param bool   $returnBase64
     * @return string
     * @throws InvalidArgumentException
     */
    public function resizeImage(string $imagePath, int $newWidth = 0, int $newHeight = 0, bool $returnBase64 = false): string
    {
        /** @var Image $image */
        $image = new Image($imagePath, $newWidth, $newHeight);

        /**
         * If the image already exists, return the path and notify via logger
         */
        if (file_exists($image->getTargetPath())) {
            $this->getLogger()->info('Image already exists, not resizing...');
            return $image->getTargetPath();
        }

        try {
            if ($this->getAdapter()->canHandle($imagePath)) {
                /** @var string $command */
                $command = $this->getAdapter()->resizeCommand($image, $returnBase64);

                $this->getLogger()->info(sprintf('Handling %s via %s', $imagePath, get_class($this->getAdapter())));
                $output = shell_exec(sprintf('%s 2>&1', $command));
                $this->getLogger()->info($output);
            }
        } catch (Exception $exception) {
            $this->getLogger()->err(
                sprintf('Exception when handling %s to via %s', $imagePath, get_class($this->getAdapter()))
            );
        }

        return $image->getTargetPath();
    }

    /**
     * @param string $imagePath
     * @param int    $cropWidth
     * @param int    $cropHeight
     * @param string $mode
     * @param int    $x
     * @param int    $y
     * @return string
     * @throws InvalidArgumentException
     */
    public function cropImage(string $imagePath, int $cropWidth, int $cropHeight, string $mode = Image::MANUAL_CROP, int $x = 0, int $y = 0): string
    {
        /** @var Image $image */
        $image = new Image($imagePath, $cropWidth, $cropHeight, $mode, $x, $y);

        /**
         * If the image already exists, return the path and notify via logger
         */
        if (file_exists($image->getTargetPath())) {
            $this->getLogger()->info('Image already exists, not resizing...');
            return $image->getTargetPath();
        }

        try {
            if ($this->getAdapter()->canHandle($imagePath)) {
                /** @var string $command */
                $command = $this->getAdapter()->cropCommand($image, $mode, $x, $y);

                $this->getLogger()->info(sprintf('Handling %s via %s', $imagePath, get_class($this->getAdapter())));
                $output = shell_exec(sprintf('%s 2>&1', $command));
                $this->getLogger()->info($output);
            }
        } catch (Exception $exception) {
            $this->getLogger()->err(
                sprintf('Exception when handling %s to via %s', $imagePath, get_class($this->getAdapter()))
            );
        }

        return $image->getTargetPath();
    }

    /**
     * @param string $imagePath
     * @param int    $cropWidth
     * @param int    $cropHeight
     * @param int    $finalWidth
     * @param int    $finalHeight
     * @param string $mode
     * @param int    $x
     * @param int    $y
     * @return string
     * @throws InvalidArgumentException
     */
    public function cropAndResize(string $imagePath, int $cropWidth, int $cropHeight, int $finalWidth, int $finalHeight, string $mode = Image::MANUAL_CROP, int $x = 0, int $y = 0): string
    {
        // If final width has not been set, set it to the cropWidth as a fallback
        if ($finalWidth === 0) {
            $finalWidth = $cropWidth;
        }
        // If final width has not been set, set it to the cropHeight as a fallback
        if ($finalWidth === 0) {
            $finalWidth = $cropWidth;
        }

        // First crop the image at the highest possible resolution for best effect
        $resized = $resizedCropped = $this->cropImage($imagePath, $cropWidth, $cropHeight, $mode, $x, $y);

        // Resize if the final height or width differs from the cropped width and height
        if ($cropWidth !== $finalWidth || $cropHeight !== $finalHeight) {
            // Resize that image to the desired final width and height
            $resizedCropped = $this->resizeImage($resized, $finalWidth, $finalHeight);

            // Delete the resized image, we dont need it anymore
            unlink($resized);
        }

        // Return the cropped and resized image
        return $resizedCropped;
    }

    /**
     * @return ImageResizerInterface
     */
    public function getAdapter(): ImageResizerInterface
    {
        return $this->adapter;
    }

    /**
     * @param ImageResizerInterface $adapter
     * @return ImageResizerService
     */
    public function setAdapter(ImageResizerInterface $adapter): ImageResizerService
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ImageResizerService
     */
    public function setLogger(LoggerInterface $logger): ImageResizerService
    {
        $this->logger = $logger;

        return $this;
    }
}