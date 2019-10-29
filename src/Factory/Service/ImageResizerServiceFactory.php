<?php

namespace Rvdlee\ZfImageResizer\Factory\Service;

use Interop\Container\ContainerInterface;
use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use Rvdlee\ZfImageResizer\Exception\InvalidConfigurationException;
use Rvdlee\ZfImageResizer\Interfaces\ImageResizerInterface;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use Zend\Log\Writer\Mock;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\Factory\FactoryInterface;

class ImageResizerServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return object|ImageResizerService
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');

        if ( ! isset($config['rvdlee']['zf-image-resizer']['adapter'])) {
            throw new InvalidConfigurationException('We are missing configuration to make this code work.');
        }

        /** @var ImageResizerInterface $adapter */
        $adapter = $container->get($config['rvdlee']['zf-image-resizer']['adapter']);

        if (isset($options['logger']) && $options['logger'] instanceof LoggerInterface) {
            $logger = $options['logger'];
        } else {
            /** @var Logger $logger */
            $logger = $container->get(Logger::class);
            /** @var Stream $writer */
            $logger->addWriter(new Mock());
        }

        return new ImageResizerService($adapter, $logger);
    }
}