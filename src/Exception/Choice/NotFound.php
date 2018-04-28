<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Choice Not Found
 */
class NotFound extends \Siwayll\Kapow\Exception
{
    /**
     * Choice name's
     */
    protected $choiceName;

    /**
     * Message of the exception
     */
    public $message = 'Choice "{choiceName}" not found';

    public $code = Level::ERROR;

    /**
     * NotFound constructor.
     */
    public function __construct(string $choiceName)
    {
        $this->choiceName = $choiceName;
    }
}
