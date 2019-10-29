<?php

namespace Rvdlee\ZfImageResizer\Controller;

use Exception;
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
        $image = $this->params()->fromRoute('image');
        if ($image === null) {
            die('You need the --image|-i param.');
        }

        /** @var string $width */
        $width = $this->params()->fromRoute('width');
        if ($width === null) {
            die('You need the --width|-w param.');
        }

        /** @var string $height */
        $height = $this->params()->fromRoute('height');
        if ($height === null) {
            die('You need the --height|-h param.');
        }

        /** @var string $modus */
        $modus = $this->params()->fromRoute('modus');
        if ($modus === null) {
            die('You need the --modus|-m param.');
        }

        /** @var string $cropModus */
        $cropModus = $this->params()->fromRoute('crop-modus');
        if ($cropModus === null) {
            die('You need the --crop-modus|-cm param.');
        }

        /** @var string $x */
        $x = $this->params()->fromRoute('x');
        if ($x === null) {
            die('You need the --x|-x param.');
        }

        /** @var string $y */
        $y = $this->params()->fromRoute('y');
        if ($y === null) {
            die('You need the --y|-y param.');
        }

        try {
            $this->getImageResizerService()->resize($image);
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