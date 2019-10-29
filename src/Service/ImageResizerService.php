<?php

namespace Rvdlee\ZfImageResizer\Service;

use Exception;
use Rvdlee\ZfImageResizer\Exception\InvalidArgumentException;
use Rvdlee\ZfImageResizer\Interfaces\ImageResizerInterface;
use Rvdlee\ZfImageResizer\Model\Image;
use Zend\Log\LoggerInterface;

class ImageResizerService
{
    /**
     * @var ImageResizerInterface
     */
    protected $adapter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ImageResizerInterface $adapter
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ImageResizerInterface $adapter, LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->getLogger()->info('Constucting ImageResizerService');

        if ( ! $adapter instanceof ImageResizerInterface) {
            throw new InvalidArgumentException(
                sprintf('Adapter configured does not have the %s interface.', ImageResizerInterface::class)
            );
        }

       $this->setAdapter($adapter);
    }

    /**
     * @return ImageResizerInterface
     */
    public function getAdapter(): ImageResizerInterface
    {
        return $this->adapter;
    }

    /**
     * @param ImageResizerInterface $adapter
     * @return ImageResizerService
     */
    public function setAdapter(ImageResizerInterface $adapter): ImageResizerService
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ImageResizerService
     */
    public function setLogger(LoggerInterface $logger) : ImageResizerService
    {
        $this->logger = $logger;

        return $this;
    }
}