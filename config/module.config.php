<?php

namespace Rvdlee\ZfImageResizer;

use Rvdlee\ZfImageResizer\Adapter\ImageMagick;
use Rvdlee\ZfImageResizer\Controller\ConsoleController;
use Rvdlee\ZfImageResizer\Factory\Adapter\ImageMagickFactory;
use Rvdlee\ZfImageResizer\Factory\Controller\ConsoleControllerFactory;
use Rvdlee\ZfImageResizer\Factory\Filter\ImageResizerFactory;
use Rvdlee\ZfImageResizer\Factory\Service\ImageResizerServiceFactory;
use Rvdlee\ZfImageResizer\Filter\ImageResizer;
use Rvdlee\ZfImageResizer\Service\ImageResizerService;

return [
    'console'         => [
        'router' => [
            'routes' => [
                'console_image_resizer' => [
                    'options' => [
                        'route'    => 'image-resizer [--image=]',
                        'defaults' => [
                            'controller' => ConsoleController::class,
                            'action'     => 'resizeImage',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            ConsoleController::class => ConsoleControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ImageResizerService::class => ImageResizerServiceFactory::class,

            ImageMagick::class  => ImageMagickFactory::class,
        ],
    ],
    'filters'   => [
        'factories' => [
            ImageResizer::class => ImageResizerFactory::class,
        ],
    ],
];