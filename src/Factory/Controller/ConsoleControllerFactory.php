<?php

namespace Rvdlee\ZfImageResizer\Factory\Controller;

use Interop\Container\ContainerInterface;
use Rvdlee\ZfImageResizer\Controller\ConsoleController;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConsoleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Logger $logger */
        $logger = $container->get(Logger::class);
        /** @var Stream $writer */
        $logger->addWriter(new Stream('php://output'));
        /** @var ImageResizerService $imageResizerService */
        $imageResizerService = $container->build(ImageResizerService::class, ['logger' => $logger]);

        return new ConsoleController($imageResizerService);
    }
}