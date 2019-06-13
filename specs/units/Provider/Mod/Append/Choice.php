<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Mod\Append;

use Gen3se\Engine\Choice as EngineChoice;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Provider;

trait Choice
{
    use OptionData;
    /**
     * Get a Choice without any special features
     */
    protected function getEyeArchChoice()
    {
        $choiceName = $this->getEyeColorChoice()->getName();
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('normal', 80))
                ->add(new Option\Data\Text('normal'))
                ->add($this->createMockAppendData($choiceName))
        );
        $optCollection->add(
            (new Option('hc', 1))
                ->add($this->createMockAppendData($choiceName))
                ->add(new Option\Data\Text('Hétérochromie centrale'))
        );
        $optCollection->add(
            (new Option('hp', 1))
                ->add($this->createMockAppendData($choiceName))
                ->add(new Option\Data\Text('Hétérochromie partielle'))
        );
        $optCollection->add(
            (new Option('v', 35))
                ->add($this->createMockAppendData($choiceName))
                ->add(new Option\Data\Text('Vairon'))
        );

        $choice = new EngineChoice('iris', $optCollection);

        return $choice;
    }

    /**
     * Get a Provider with simple choices
     */
    protected function getProviderWithAppendModChoices(): Provider
    {
        $provider = new Provider();
        $provider
            ->add($this->getEyeArchChoice())
            ->add($this->getEyeColorChoice())
        ;
        return $provider;
    }
}
