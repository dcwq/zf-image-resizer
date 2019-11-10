<?php

namespace Rvdlee\ZfImageResizer\Controller;

use Exception;
use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
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
        if ($image === null) {
            throw new InvalidArgumentException('You need the --image|-i param.');
        }

        /** @var string $width */
        $width = $this->params()->fromRoute('width', null);
        if ($width === null) {
            throw new InvalidArgumentException('You need the --width|-w param.');
        }

        /** @var string $height */
        $height = $this->params()->fromRoute('height', null);
        if ($height === null) {
            throw new InvalidArgumentException('You need the --height|-h param.');
        }

        /** @var string $modus */
        $modus = $this->params()->fromRoute('modus', null);
        if ($modus === null) {
            throw new InvalidArgumentException('You need the --modus|-m param.');
        }

        /** @var string $cropModus */
        $cropModus = $this->params()->fromRoute('crop-modus', null);
        if ($cropModus === null) {
            throw new InvalidArgumentException('You need the --crop-modus|-cm param.');
        }

        /** @var string $x */
        $x = $this->params()->fromRoute('x', null);
        if ($x === null) {
            throw new InvalidArgumentException('You need the --x|-x param.');
        }

        /** @var string $y */
        $y = $this->params()->fromRoute('y', null);
        if ($y === null) {
            throw new InvalidArgumentException('You need the --y|-y param.');
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