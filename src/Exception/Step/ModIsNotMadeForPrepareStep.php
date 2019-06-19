<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Step;

use Gen3se\Engine\Mod\ModInterface;
use Siwayll\Kapow\Level;

class ModIsNotMadeForPrepareStep extends \Siwayll\Kapow\Exception
{
    protected $modClass;

    public function __construct(ModInterface $mod)
    {
        parent::__construct(
            'Mod {modClass} is not made for Prepare step',
            Level::ERROR
        );
        $this->modClass = \get_class($mod);
    }
}
