<?php

namespace Rvdlee\ZfImageResizer\Filter;

use Exception;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\Filter\AbstractFilter;

class ImageResizer extends AbstractFilter
{
    /**
     * @var ImageResizerService
     */
    protected $imageResizerService;

    public function __construct(ImageResizerService $imageResizerService)
    {
        $this->setImageResizerService($imageResizerService);
    }

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
}