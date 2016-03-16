<?php

use \mageekguy\atoum;

$report = $script->addDefaultReport();

// This will add a green or red logo after each run depending on its status.
$report->addField(new atoum\report\fields\runner\result\logo());

$buildDir = __DIR__ . '/../Tests/build';
if (!is_dir($buildDir)) {
    mkdir($buildDir);
    mkdir($buildDir . '/html');
}
$coverageField = new atoum\report\fields\runner\coverage\html('Bot', $buildDir . '/html');
$report->addField($coverageField);


$cloverWriter = new atoum\writers\file($buildDir . '/atoum.clover.xml');
$cloverReport = new atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);
$runner->addReport($cloverReport);

// Chargement du fichier bootstrap
$runner->setBootstrapFile(__DIR__ . '/../Tests/Units/.bootstrap.atoum.php');
