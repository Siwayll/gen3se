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
    protected function getEyeColorChoice()
    {
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('bleu', 30))
                ->set('text', 'les yeux bleus')
        );
        $optCollection->add(
            (new Option('vert', 15))
                ->set('text', 'les yeux verts')
        );
        $optCollection->add(
            (new Option('marron', 150))
                ->set('text', 'les yeux marrons')
        );
        $optCollection->add(
            (new Option('violet', 1))
                ->set('text', 'les yeux violets')
        );

        $choice = new Choice('yeux', $optCollection);

        return $choice;
    }
}
