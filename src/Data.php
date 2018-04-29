<?php declare(strict_types = 1);

namespace Gen3se\Engine;


interface Data
{
//    public function exportTo(DataFormat $format): DataFormat;

    public function registersTo($data, $placement): void;
}