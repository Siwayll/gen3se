<?php
namespace Gen3se\Engine\Choice;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Rand;
use Siwayll\Kapow\Exception;

class Preparer
{
    private $choice;

    public function __construct(Choice $choice)
    {
        $this->choice = clone $choice;
    }

    public function getLoadedChoice(): Choice
    {
        return $this->choice;
    }
}
