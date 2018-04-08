<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Engine;

use Gen3se\Engine\Mod\InstructionInterface;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Instruction already present
 */
class InstructionAlreadyPresent extends Exception
{
    /**
     * Message of the exception
     */
    public $message = 'Instruction "{newInstructionCode}" is already present';

    public $code = Level::ERROR;

    protected $newInstructionCode;

    public function __construct(string $newInstructionCode)
    {
        $this->newInstructionCode = $newInstructionCode;
    }
}
