<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Exception;

/**
 * Class ChoiceMustHaveNonEmptyCollectionOfOptions
 * @package Gen3se\Engine\Exception
 */
class MustHaveNonEmptyCollectionOfOptions extends \Siwayll\Kapow\Exception
{
    /**
     * @var string
     */
    protected $choiceName;

    /**
     * @var string
     */
    public $message = 'Choice {choiceName} must have a non-empty collection of Option';

    /**
     * @var int
     */
    public $code = 400;

    /**
     * ChoiceMustHaveNonEmptyCollectionOfOptions constructor.
     */
    public function __construct(string $choiceName)
    {
        $this->choiceName = $choiceName;
    }
}
