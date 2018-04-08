<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Mod\StepableInterface;

/**
 * Indicate whether a mod can handle the execution of a process during the step __Prepare__
 */
interface IsPrepareReady extends StepableInterface
{
    public function execPrepare(Choice $choice): void;
}
