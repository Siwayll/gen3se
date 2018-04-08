<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Exception;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;

class ExceptionWithChoiceName extends Exception implements ChoiceNameInterface
{
    use ChoiceNameTrait;
}
