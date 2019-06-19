<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class PositionMustBeRelevent extends \Siwayll\Kapow\Exception
{
    use ChoiceNameTrait;

    protected $stackPosition;

    protected $maxWeight;

    public function __construct(int $stackPosition, int $maxWeight)
    {
        parent::__construct(
            'Position "{stackPosition}" must be relevant in [0,{maxWeight}] for {choiceName}',
            Level::CRITICAL
        );
        $this->stackPosition = (string) $stackPosition;
        $this->maxWeight = (string) $maxWeight;
    }
}
