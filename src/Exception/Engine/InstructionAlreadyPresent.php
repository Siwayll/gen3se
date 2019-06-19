<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Engine;

use Gen3se\Engine\Mod\InstructionInterface;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class InstructionAlreadyPresent extends \Siwayll\Kapow\Exception
{
    /** @var string */
    protected $newInstructionCode;

    public function __construct(string $newInstructionCode)
    {
        parent::__construct(
            'Instruction "{newInstructionCode}" is already present',
            Level::ERROR
        );
        $this->newInstructionCode = $newInstructionCode;
    }
}
