<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class NotFoundInStack extends \Siwayll\Kapow\Exception implements ChoiceNameInterface
{
    use ChoiceNameTrait;

    protected $stackPosition;

    public function __construct(int $stackPosition)
    {
        parent::__construct(
            'Cannot find options in collection at stack position "{stackPosition}" for {choiceName}',
            Level::ERROR
        );
        $this->stackPosition = (string) $stackPosition;
    }
}
