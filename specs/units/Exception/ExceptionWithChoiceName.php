<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Exception;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception as Kapow;

class ExceptionWithChoiceName extends Kapow implements ChoiceNameInterface
{
    use ChoiceNameTrait;
}
