<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Step;

use Gen3se\Engine\Mod\ModInterface;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Mod not made for Prepare Stop
 */
class ModIsNotMadeForPrepareStep extends \Siwayll\Kapow\Exception
{
    /**
     * Mod class name
     */
    protected $modClass;

    /**
     * Message of the exception
     */
    public $message = 'Mod {modClass} is not made for Prepare step';

    public $code = Level::ERROR;

    public function __construct(ModInterface $mod)
    {
        $this->modClass = \get_class($mod);
    }
}
