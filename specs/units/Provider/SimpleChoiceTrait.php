<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Collection as MockOptionCollectionProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Option\Data as MockOptionDataProvider;

trait SimpleChoiceTrait
{
    use MockOptionCollectionProvider;
    use MockOptionDataProvider;

    /**
     * Get a Choice without any special features
     */
    protected function getEyeColorChoice()
    {
        throw new \RuntimeException('Use alternative to getEyeColorChoice');
    }

    /**
     * Get a Choice without any special features
     */
    protected function getHairColorChoice()
    {
        throw new \RuntimeException('Use alternative to getHairColorChoice');
    }
}
