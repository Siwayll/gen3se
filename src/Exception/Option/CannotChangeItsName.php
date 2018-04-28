<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class OptionsCannotChangeItsName
 * @package Gen3se\Engine\Exception
 */
class CannotChangeItsName extends \Siwayll\Kapow\Exception
{
    /**
     * @var string
     */
    protected $optionName;

    /**
     * @var string
     */
    public $message = 'Option {optionName} cannot change its name';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * OptionsCannotChangeItsName constructor.
     * @param string $optionName
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
