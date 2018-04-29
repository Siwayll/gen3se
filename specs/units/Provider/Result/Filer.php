<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Result;

trait Filer
{
    protected function createMockFiler(
        ?string ...$depth
    ) {
        $depth = $depth ?? $this->createDepth();
        $mock = $this->newMockInstance(\Gen3se\Engine\Result\Filer::class);
        $mock->getMockController()->getDepth = $depth;

        return $mock;
    }

    private function createDepth(): array
    {
        $depth = [];
        $deepness = \rand(1, 10);
        for ($i = 1; $i <= $deepness; $i++) {
            $depth[] = \uniqid('depth_');
        }

        return $depth;
    }
}
