<?php

namespace Gen3se\Engine\Specs\Units\Provider;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Option\Collection;
use Gen3se\Engine\Option\Option;

/**
 * Trait SimpleChoiceTrait
 * @package Gen3se\Engine\Specs\Units\Provider
 */
trait SimpleChoiceTrait
{
    /**
     * Get a Choice without any special features
     */
    protected function getEyeColorChoice()
    {
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('blue', 30))
                ->set('text', 'bleu')
        );
        $optCollection->add(
            (new Option('green', 15))
                ->set('text', 'vert')
        );
        $optCollection->add(
            (new Option('marron', 150))
                ->set('text', 'marron')
        );
        $optCollection->add(
            (new Option('purple', 1))
                ->set('text', 'violet')
        );

        $choice = new Choice('eyeColor', $optCollection);

        return $choice;
    }

    /**
     * Get a Choice without any special features
     */
    protected function getHairColorChoice()
    {
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('noir', 300))
                ->set('text', 'les cheveux noirs')
        );
        $optCollection->add(
            (new Option('blond', 100))
                ->set('text', 'les cheveux blonds')
        );
        $optCollection->add(
            (new Option('vert', 5))
                ->set('text', 'les cheveux verts')
        );
        $optCollection->add(
            (new Option('violet', 1))
                ->set('text', 'les cheveux violets')
        );

        $choice = new Choice('cheveux', $optCollection);

        return $choice;
    }

    /**
     * Get a Provider with simple choices
     */
    protected function getProviderWithSimpleChoices(): Provider
    {
        $provider = new Provider();
        $provider
            ->add($this->getEyeColorChoice())
            ->add($this->getHairColorChoice())
        ;
        return $provider;
    }
}
