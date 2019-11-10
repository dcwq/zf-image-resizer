<?php

namespace Rvdlee\ZfImageResizer\Controller;

use Exception;
use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use Rvdlee\ZfImageResizer\Model\Image;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
    /**
     * @var ImageResizerService
     */
    protected $imageResizerService;

    public function __construct(ImageResizerService $imageResizerService)
    {
        $this->setImageResizerService($imageResizerService);
    }

    /**
     * Console wrapper for service
     *
     * @throws Exception
     */
    public function resizeImageAction()
    {
        /** @var string $image */
        $image = $this->params()->fromRoute('image', null);
        if ( ! file_exists($image)) {
            throw new InvalidArgumentException('You need the --image|-i param to be an existing image.');
        }

        /** @var int $width */
        $width = (int) $this->params()->fromRoute('width', 100);
        /** @var int $height */
        $height = (int) $this->params()->fromRoute('height', 100);

        try {
            $this->getImageResizerService()->resizeImage($image, $width, $height);
        } catch (Exception $exception) {
            throw $exception;
        }
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
     * @return ConsoleController
     */
    public function setImageResizerService(ImageResizerService $imageResizerService): ConsoleController
    {
        $this->imageResizerService = $imageResizerService;
        return $this;
    }
}