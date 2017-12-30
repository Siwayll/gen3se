<?php

use Kitab\Compiler\Target\Html\Configuration;

$configuration = new Configuration();

$configuration->defaultNamespace = 'Gen3se\Engine';
$configuration->projectName = 'Gen3se Engine';
$configuration->composerFile = __DIR__ . '/composer.json';
$configuration->logoURL = 'http://git.doublepsi.fr/uploads/-/system/project/avatar/4/engineering_by_baddad.jpg';

return $configuration;
