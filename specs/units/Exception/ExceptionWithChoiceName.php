<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Exception;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;

class ExceptionWithChoiceName extends \Siwayll\Kapow\Exception implements ChoiceNameInterface
{
    public function setChoiceName(string $choiceName): void
    {
    }
}
