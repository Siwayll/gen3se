<?php declare(strict_types = 1);

namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Exception\Step\ModIsNotMadeForPrepareStep;
use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Mod\StepableInterface;

/**
 * Prepares Choice for its resolution
 */
class Prepare
{
    public const STEP_NAME = '>prepare';

    private $choice;

    public function __construct(Choice $choice, ModCollection $modCollection)
    {
        $this->choice = clone $choice;

        /** @var StepableInterface $mod */
        foreach ($modCollection->getModForStep(self::STEP_NAME) as $mod) {
            if (!$mod instanceof IsPrepareReady) {
                throw new ModIsNotMadeForPrepareStep($mod);
            }
            $mod->execPrepare($this->choice);
        }
    }

    /**
     * Get the prepared choice
     */
    public function __invoke(): Choice
    {
        return $this->choice;
    }
}
