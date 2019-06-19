<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class MustHaveNonEmptyCollectionOfOptions extends \Siwayll\Kapow\Exception
{
    /** @var string */
    protected $choiceName;

    public function __construct(string $choiceName)
    {
        parent::__construct(
            'Choice {choiceName} must have a non-empty collection of Option',
            Level::ERROR
        );
        $this->choiceName = $choiceName;
    }
}
