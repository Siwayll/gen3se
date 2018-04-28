<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class PositionMustBeRelevent
 * @package Gen3se\Engine\Exception
 */
class PositionMustBeRelevent extends \Siwayll\Kapow\Exception
{
    use ChoiceNameTrait;

    /**
     * @var string
     */
    protected $stackPosition;

    /**
     * @var string
     */
    protected $maxWeight;

    /**
     * @var string
     */
    public $message = 'Position "{stackPosition}" must be relevant in [0,{maxWeight}] for {choiceName}';

    /**
     * @var int
     */
    public $code = Level::CRITICAL;

    /**
     * PositionMustBeRelevent constructor.
     * @param int $stackPosition
     * @param int $maxWeight
     */
    public function __construct(int $stackPosition, int $maxWeight)
    {
        $this->stackPosition = (string) $stackPosition;
        $this->maxWeight = (string) $maxWeight;
    }
}
