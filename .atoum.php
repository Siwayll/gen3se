<?php

$runner
    ->addTestsFromDirectory(__DIR__ . '/tests/units/')
    ->disallowUsageOfUndefinedMethodInMock()
    ->setBootstrapFile(__DIR__ . '/tests/units/.bootstrap.atoum.php');
;
