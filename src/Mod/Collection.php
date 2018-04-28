<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod;

/**
 * List of Mods
 */
class Collection implements \Countable
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * Add a Mod in the list
     */
    public function add(ModInterface $mod): self
    {
        $this->container[] = $mod;

        return $this;
    }

    /**
     * Count number of Mods in the list
     */
    public function count(): int
    {
        return \count($this->container);
    }

    /**
     * Yield all the mods for the step $stepName
     */
    public function getModForStep(string $stepName): \Generator
    {
        foreach ($this->container as $mod) {
            if (!$mod instanceof StepableInterface) {
                continue;
            }
            if (!$mod->isUpForStep($stepName)) {
                continue;
            }
            yield $mod;
        }
    }
}
