<?php

namespace Gen3se\Engine\Tests\Units\Provider;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Collection;
use Gen3se\Engine\Option\Option;

/**
 * Trait SimpleChoiceTrait
 * @package Gen3se\Engine\Tests\Units\Provider
 */
trait SimpleChoiceTrait
{
    /**
     * @return Choice
     */
    protected function getEyeColorChoice()
    {
        $optCollection = new Collection();
        $optCollection->add(new Option('bleu', 30));
        $optCollection->add(new Option('vert', 15));
        $optCollection->add(new Option('marron', 150));
        $optCollection->add(new Option('violet', 1));

        $choice = new Choice('yeux', $optCollection);

        return $choice;
    }
}
