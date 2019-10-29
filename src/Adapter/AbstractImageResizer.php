<?php

namespace Rvdlee\ZfImageResizer\Adapter;

use Rvdlee\ZfImageResizer\Interfaces\ImageResizerInterface;
use Rvdlee\ZfImageResizer\Model\Image;
use Zend\Validator\ValidatorChain;

abstract class AbstractImageResizer implements ImageResizerInterface
{
    /**
     * @var array
     */
    protected $binaryOptions;

    /**
     * @var string
     */
    protected $binaryPath;

    /**
     * @var ValidatorChain
     */
    protected $validatorChain;

    /**
     * AbstractImageResizer constructor.
     *
     * @param array          $binaryOptions
     * @param ValidatorChain $validatorChain
     */
    public function __construct(array $binaryOptions, ValidatorChain $validatorChain)
    {
        $this->setBinaryOptions($binaryOptions)
            ->setValidatorChain($validatorChain);
    }

    public function resizeCommand(Image $image) : string
    {
        return sprintf('%s %s %s', $this->getBinaryPath(), implode(' ', $this->getBinaryOptions()), $image->getPath());
    }

    /**
     * In the Abstract class we just run the validator stack
     * that has been configured, you can override ofcourse.
     *
     * @param string $imagePath
     *
     * @return bool
     */
    public function canHandle(string $imagePath) : bool
    {
        return $this->getValidatorChain()->isValid($imagePath);
    }

    /**
     * @return array
     */
    public function getBinaryOptions() : array
    {
        return $this->binaryOptions;
    }

    /**
     * @param array $binaryOptions
     *
     * @return AbstractImageResizer
     */
    public function setBinaryOptions(array $binaryOptions) : AbstractImageResizer
    {
        $this->binaryOptions = $binaryOptions;

        return $this;
    }

    /**
     * @return string
     */
    public function getBinaryPath() : string
    {
        return $this->binaryPath;
    }

    /**
     * @param string $binaryPath
     *
     * @return AbstractImageResizer
     */
    public function setBinaryPath(string $binaryPath) : AbstractImageResizer
    {
        $this->binaryPath = $binaryPath;

        return $this;
    }

    /**
     * @return ValidatorChain
     */
    public function getValidatorChain() : ValidatorChain
    {
        return $this->validatorChain;
    }

    /**
     * @param ValidatorChain $validatorChain
     *
     * @return AbstractImageResizer
     */
    public function setValidatorChain(ValidatorChain $validatorChain) : AbstractImageResizer
    {
        $this->validatorChain = $validatorChain;

        return $this;
    }
}