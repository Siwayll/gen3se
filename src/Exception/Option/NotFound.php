<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Exception;

/**
 * Class OptionNotFound
 * @package Gen3se\Engine\Exception
 */
class NotFound extends \Siwayll\Kapow\Exception
{
    protected $optionName;

    public $message = 'Option {optionName} not found';

    public $code = 400;

    /**
     * OptionMustHaveNonEmptyName constructor.
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
