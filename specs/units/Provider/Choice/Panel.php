<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Choice;

trait Panel
{

    protected function createPanelMock(
        ?int $count = 0,
        ?\Gen3se\Engine\Choice\Panel $clone = null
    ) {
        $panel = $this->newMockInstance(\Gen3se\Engine\Choice\Panel::class);
        $this->calling($panel)->count = $count;

        if ($clone === null) {
            $clone = $panel;
        }
        $this->calling($panel)->copy = $clone;

        return $panel;
    }
}
