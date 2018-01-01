<?php
namespace Gen3se\Engine\Step;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Mod\StepableInterface;

/**
 * Prepares Choice for its resolution
 */
class Prepare
{
    const STEP_NAME = '>prepare';

    private $choice;

    public function __construct(Choice $choice, ModCollection $modCollection)
    {
        $this->choice = clone $choice;

        /** @var StepableInterface $mod */
        foreach ($modCollection->getModForStep(self::STEP_NAME) as $mod) {
            if (!$mod instanceof IsPrepareReady) {
                continue;
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
