This is a image resizer package. I've written this with ZF3 in mind, everything is written with configuration over convention in mind. Highly extendible and easy in use. You can use this along side my other package to optimise images.

Key features of this package is:

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

The model has  a few calculations build in and some fallback logic when certain parameters are not provided. A good example would be the ratio scaling when only the width or height is provided.

Other calculations included is to crop based on one of nine locations on the image. Here are a few examples of crops. The [beautiful image](https://unsplash.com/photos/CQl3Y5bV6FA) was published under a free license by [Scott Walsh](https://unsplash.com/@outsighted). Thanks for doing that!

Here we have the full image. I resized the original photo for the example because its quality and resolution was a bit high to illustrate an image resizer.

![Full image, by ](https://github.com/rvdlee/zf-image-resizer/raw/master/examples/full.jpg)

Let's start simple. An image resize. You can resize the following image using one of the two methods, you can define the width and height `$imageResizerService->resizeImage($file->getPath(), 300, 200)` or simply only provide the width `$imageResizerService->resizeImage($file->getPath(), 300)` or height `$imageResizerService->resizeImage($file->getPath(), 0, 200)`. When you only provide either of them, it will rescale the image by ratio.

![Resized to 300x200](https://github.com/rvdlee/zf-image-resizer/raw/master/examples/resized.jpg)

Then there is a simple crop. We are going to use build-in calculations to crop the center of the image with a defined cropped width and height. `$imageResizerService->cropImage($file->getPath(), 350, 350, Image::CENTERED_CROP)))`.

![Cropped 350x350 Centered](https://github.com/rvdlee/zf-image-resizer/raw/master/examples/centered_crop.jpg)

You are the master of your own crop and you can define the width and height how you want. Choosing the centered or any other mode already provided by the package. `$imageResizerService->cropImage($file->getPath(), 300, 450, Image::CENTERED_CROP)`

![Cropped 300x450 Centered](https://github.com/rvdlee/zf-image-resizer/raw/master/examples/freeform_crop.jpg)

Additionally, there is also an manual mode if you want full control. The last two parameters are the `x` and `y` coordinates of the crop.`$imageResizerService->cropImage($file->getPath(), 400, 400, Image::MANUAL_CROP, 0, 400)`

![Manual 400x400 crop with own coordinates](https://github.com/rvdlee/zf-image-resizer/raw/master/examples/manual_crop.jpg)



# InputFilters

Just like my [optimiser package](https://github.com/rvdlee/zf-image-optimiser) we support InputFilters to make resizing a breeze when uploading photo's like avatars for example. Passing along the `Image::CENTERED_CROP` option to the service object automatically makes a centered crop of the original video.

Using an InputFilter is easy.

```php
$this->add(
    [
        'type'       => FileInput::class,
        'name'       => 'avatar',
        'required'   => false,
        'filters'    => [
           	# ... Other filters
            [
                'name'    => ImageResizer::class,
                'options' => [
                    'mode'        => Image::ONLY_CROP_MODUS,
                    'crop_mode'   => Image::CENTERED_CROP,
                    'crop_width'  => 100,
                    'crop_height' => 100,
                ],
            ],
        ],
        'validators' => [
            # ... Validators
        ],
    ]
);
```



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

