<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

trait Step
{
    protected function createMockStep(?callable $stepTreatment)
    {
        $stepTreatment = $stepTreatment ?? function () {
        };

        $mock = $this->newMockInstance(\Gen3se\Engine\Step::class);
        $mock->getMockController()->__invoke = $stepTreatment;

        return $mock;
    }
}
