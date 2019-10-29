<?php

namespace Rvdlee\ZfImageResizer\Factory\Filter;

use Interop\Container\ContainerInterface;
use Rvdlee\ZfImageResizer\Filter\ImageResizer;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\ServiceManager\Factory\FactoryInterface;

class ImageResizerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ImageResizerService $imageResizerService */
        $imageResizerService = $container->get(ImageResizerService::class);

        return new ImageResizer($imageResizerService);
    }
}