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
        'target_height' => 0,
        'target_width'  => 0,
        'x'             => 0,
        'y'             => 0,
    ];

    public function __construct()
    {
        $this->setOptions(array_merge($this->options, $options));
    }

    /**
     * ImageResizer constructor.
     *
     * @param ImageResizerService $imageResizerService
     */
    public function __construct(ImageResizerService $imageResizerService)
    {
        $this->setImageResizerService($imageResizerService);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function filter($value)
    {
        try {
            $this->getImageResizerService()->resize($value['tmp_name']);
        } catch (Exception $exception) {
            throw $exception;
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
     * @param string $mode
     * @return self
     */
    public function setMode($mode = Image::ONLY_RESIZE_MODUS)
    {
        $this->options['mode'] = $mode;
        return $this;
    }
}