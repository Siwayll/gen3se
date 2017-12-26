<?php

namespace Gen3se\Engine\Tests\Units;

use mageekguy\atoum;
use mageekguy\atoum\mock;

class Test extends atoum\spec
{
    public function beforeTestMethod($method)
    {
        mock\controller::disableAutoBindForNewMock();

        $this->mockGenerator
            ->allIsInterface()
            ->eachInstanceIsUnique()
        ;

        return parent::beforeTestMethod($method);
    }
}
