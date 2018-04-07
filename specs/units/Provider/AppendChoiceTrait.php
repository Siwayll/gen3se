<?php

namespace Gen3se\Engine\Specs\Units\Provider;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option;

trait AppendChoiceTrait
{
    /**
     * Get a Choice without any special features
     */
    protected function getEyeArchChoice()
    {
        $choiceName = $this->getEyeColorChoice()->getName();
        $optCollection = new Collection();
        $optCollection->add(
            (new Option('normal', 80))
                ->set('text', 'normal')
                ->set('scenario.append', $choiceName)
        );
        $optCollection->add(
            (new Option('hc', 1))
                ->set('scenario.append', $choiceName)
                ->set('text', 'Hétérochromie centrale')
        );
        $optCollection->add(
            (new Option('hp', 1))
                ->set('scenario.append', $choiceName)
                ->set('text', 'Hétérochromie partielle')
        );
        $optCollection->add(
            (new Option('v', 35))
                ->set('scenario.append', $choiceName)
                ->set('text', 'Vairon')
        );

        $choice = new Choice('iris', $optCollection);

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
