<?php

namespace Gen3se\Engine\Choice;


trait OptionCleanerTrait
{
    public final function cleanOption(array $option)
    {
        unset(
            $option['mod'],
            $option['weight'],
            $option['tags'],
            $option['name']
        );

        return $option;
    }
}
