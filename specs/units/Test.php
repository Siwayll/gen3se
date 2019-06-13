<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core;

use mageekguy\atoum;
use mageekguy\atoum\mock;

abstract class Test extends atoum\bdd\spec
{
    public function __construct(
        ?atoum\adapter $adapter = null,
        ?atoum\annotations\extractor $annotationExtractor = null,
        ?atoum\asserter\generator $asserterGenerator = null,
        ?atoum\test\assertion\manager $assertionManager = null,
        ?\closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );
        $this->getAsserterGenerator()->addNamespace('Siwayll\Kapow\Atoum');
    }

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
