<?php

namespace Rvdlee\ZfImageResizer\Factory\Adapter;

use Interop\Container\ContainerInterface;
use Rvdlee\ZfImageResizer\Adapter\ImageMagick;

class ImageMagickFactory extends AbstractImageResizerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $args */
        $args = parent::__invoke($container, $requestedName, $options);

        return new ImageMagick(...$args);
    }
}