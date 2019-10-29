<?php

namespace Rvdlee\ZfImageResizer;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleUsage(Console $console)
    {
        return [
            // Describe available commands
            'image-resizer [--image] [--width] [--height] [--modus] [--x] [--y]' => 'Resize the given image',

            // Describe expected parameters
            ['--image|-i', 'Provide the image you want to resize'],
            ['--width|-w', 'Target width, can be optional if height is provided (ratio scale)'],
            ['--height|-h', 'Target height, can be optional if width is provided (ratio scale)'],
            ['--modus|-m', 'resize|resize-and-crop|crop'],
            ['--crop-modus|-cm', 'manual|upper-left|upper-middle|upper-right|middle-left|centered|middle-right|bottom-left|bottom-middle|bottom-right'],
            ['--x|-x', 'Provide x-offset from the top-left corner'],
            ['--y|-y', 'Provide y-offset from the top-left corner'],
        ];
    }
}

