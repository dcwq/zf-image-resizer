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
    public function getMode()
    {
        return $this->options['mode'];
    }

    /**
     * @return string
     */
    public function getCropMode()
    {
        return $this->options['crop_mode'];
    }

    /**
     * @return string
     */
    public function getResizeWidth()
    {
        return $this->options['resize_width'];
    }

    /**
     * @return string
     */
    public function getResizeHeight()
    {
        return $this->options['resize_height'];
    }

    /**
     * @return string
     */
    public function getCropWidth()
    {
        return $this->options['crop_width'];
    }

    /**
     * @return string
     */
    public function getCropHeight()
    {
        return $this->options['crop_height'];
    }

    /**
     * @return string
     */
    public function getX()
    {
        return $this->options['x'];
    }

    /**
     * @return string
     */
    public function getY()
    {
        return $this->options['y'];
    }
}