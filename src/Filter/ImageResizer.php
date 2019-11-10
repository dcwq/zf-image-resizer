<?php

namespace Rvdlee\ZfImageResizer\Filter;

use Exception;
use Rvdlee\ZfImageResizer\Model\Image;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\Filter\AbstractFilter;

class ImageResizer extends AbstractFilter
{
    /**
     * @var ImageResizerService
     */
    protected $imageResizerService;

    /**
     * @var array
     */
    protected $options = [
        'mode'          => Image::ONLY_RESIZE_MODUS,
        'crop_mode'     => Image::CENTERED_CROP,
        'resize_width'  => 0,
        'resize_height' => 0,
        'crop_width'    => 0,
        'crop_height'   => 0,
        'x'             => 0,
        'y'             => 0,
    ];

    /**
     * ImageResizer constructor.
     *
     * @param ImageResizerService $imageResizerService
     * @param array               $options
     */
    public function __construct(ImageResizerService $imageResizerService, array $options = [])
    {
        $this->setOptions(array_merge($this->options, $options))
             ->setImageResizerService($imageResizerService);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function filter($value)
    {
        switch ($this->getMode()) {
            case Image::ONLY_RESIZE_MODUS:
                try {
                    $this->getImageResizerService()->resizeImage(
                        $value['tmp_name'],
                        $this->getResizeWidth(),
                        $this->getResizeHeight()
                    );
                } catch (Exception $exception) {
                    throw $exception;
                }
            break;
            case Image::ONLY_CROP_MODUS:
                try {
                    $this->getImageResizerService()->cropImage(
                        $value['tmp_name'],
                        $this->getCropWidth(),
                        $this->getCropHeight(),
                        $this->getCropMode(),
                        $this->getX(),
                        $this->getY()
                    );
                } catch (Exception $exception) {
                    throw $exception;
                }
            break;
            case Image::CROP_AND_RESIZE_MODUS:
                try {
                    $this->getImageResizerService()->cropAndResize(
                        $value['tmp_name'],
                        $this->getCropWidth(),
                        $this->getCropHeight(),
                        $this->getResizeWidth(),
                        $this->getResizeHeight(),
                        $this->getCropMode(),
                        $this->getX(),
                        $this->getY()
                    );
                } catch (Exception $exception) {
                    throw $exception;
                }
            break;
        }

        return $value;
    }

    /**
     * @return ImageResizerService
     */
    public function getImageResizerService(): ImageResizerService
    {
        return $this->imageResizerService;
    }

    /**
     * @param ImageResizerService $imageResizerService
     * @return ImageResizer
     */
    public function setImageResizerService(ImageResizerService $imageResizerService): ImageResizer
    {
        $this->imageResizerService = $imageResizerService;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return (string) $this->options['mode'];
    }

    /**
     * @return string
     */
    public function getCropMode(): string
    {
        return $this->options['crop_mode'];
    }

    /**
     * @return int
     */
    public function getResizeWidth(): int
    {
        return (int) $this->options['resize_width'];
    }

    /**
     * @return int
     */
    public function getResizeHeight(): int
    {
        return (int) $this->options['resize_height'];
    }

    /**
     * @return int
     */
    public function getCropWidth(): int
    {
        return (int) $this->options['crop_width'];
    }

    /**
     * @return int
     */
    public function getCropHeight(): int
    {
        return (int) $this->options['crop_height'];
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return (int) $this->options['x'];
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return (int) $this->options['y'];
    }
}