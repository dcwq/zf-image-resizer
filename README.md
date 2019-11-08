This is a image resizer package. I've written this with ZF3 in mind, everything is written with configuration over convention in mind. Highly extendible and easy in use. You can use this along side my other package to optimise images.

Key features of this package is:

* Resizing through CLI
* Resizing through InputFilters
* Resizing through the Service object
* Resizing on the fly

# Usage

To get started you need to choose one of the programs to resize with. By default we support [ImageMagick ](https://imagemagick.org/index.php) out of the box. It is mainstream and does a fantastic job at resizing images while maintaining great image quality.

You will need a bit of configuration to get started. We kept the validation chain simple since ImageMagick support over 200 formats. If you are writing your own adapter you now have the option to validate if the image is suitable for resizing through that adapter.

```php
# ... config use statements

return [
    'rvdlee' => [
        'zf-image-resizer'   => [
            'adapter'         => ImageMagick::class,
            'validator-chain' => [
                ['name' => IsImage::class],
            ],
        ],
    ],
]; 
```

# InputFilters

Just like my [optimiser package](https://github.com/rvdlee/zf-image-optimiser) we support InputFilters to make resizing a breeze when uploading photo's like avatars for example. Passing along the `Image::CENTERED_CROP` option to the service object automatically makes a centered crop of the original video.

# Commandline

# Service

The service object is the baseline of the resizer. You can use DI to instance this anywhere in your zend application. We also use support logging to catch all output from the programs used to resize images.

The service by default gets decked out with a default `Zend\Log\Writer\Mock` writer. You can still access the logs in this writer. You can override  this when building the service. Allowing you to provide a DB, Logfile or Stdout writer.

```php
class SomeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ImageResizerService $imageOptimiserService */
        $imageResizerService = $container->get(ImageResizerService::class);

        # or... provide your own LoggerInterface

        /** @var Logger $logger */
        $logger = $container->get(Logger::class);
        /** @var Stream $writer */
        $logger->addWriter(new Stream('php://output'));
        /** @var ImageResizerService $imageOptimiserService */
        $imageResizerService = $container->build(ImageResizerService::class, ['logger' => $logger]);

        # ... other factory stuff
    }
}
```

If you want to access the logs in the Mock writer, just use the  following snippet. Locate the Mock writer and then look at the events.

```php
/** @var array|WriterInterface[] $writers */
$writers = $imageResizerService->getLogger()->getWriters()->toArray();
/** @var Zend\Log\Writer\Mock $mockWriter */
$mockWriter = $writers[0];
/** @var array $events */
$events = $mockWriter->events;
```

