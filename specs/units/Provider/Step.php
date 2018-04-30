<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

use Gen3se\Engine\Step\PostResolve;
use Gen3se\Engine\Step\Primary;
use Gen3se\Engine\Step\Resolve;

trait Step
{
    protected function createMockStep(
        ?string $stepInterfaceName = null,
        $stepTreatment = null
    ) {
        $stepTreatment = $stepTreatment ?? function () {
        };

        $stepInterfaceName = $stepInterfaceName ?? \Gen3se\Engine\Step::class;

        $mock = $this->newMockInstance($stepInterfaceName);

        switch ($stepInterfaceName) {
            case Primary::class:
            case Resolve::class:
            case PostResolve::class:
                // @todo add ctrl to $stepTreatment type
                $mock->getMockController()->__invoke = $stepTreatment;
                break;
        }

        return $mock;
    }
}
