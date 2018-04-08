<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Specs\Units\Provider\Choice\Option\Data as OptionDataProvider;

/**
 * Trait SimpleChoiceTrait
 * @package Gen3se\Engine\Specs\Units\Provider
 */
trait SimpleChoiceTrait
{
    use OptionDataProvider;

    /**
     * Get a Choice without any special features
     */
    protected function getEyeColorChoice()
    {
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('blue', 30))
                ->add($this->createMockOptionData('bleu'))
        );
        $optCollection->add(
            (new Option('green', 15))
                ->add(new Option\Data\Text('vert'))
        );
        $optCollection->add(
            (new Option('marron', 150))
                ->add(new Option\Data\Text('marron'))
        );
        $optCollection->add(
            (new Option('purple', 1))
                ->add(new Option\Data\Text('violet'))
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
                ->add(new Option\Data\Text('les cheveux noirs'))
        );
        $optCollection->add(
            (new Option('blond', 100))
                ->add(new Option\Data\Text('les cheveux blonds'))
        );
        $optCollection->add(
            (new Option('vert', 5))
                ->add(new Option\Data\Text('les cheveux verts'))
        );
        $optCollection->add(
            (new Option('violet', 1))
                ->add(new Option\Data\Text('les cheveux violets'))
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
