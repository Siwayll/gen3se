<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface DataStorage
{
    public function addData(Data $data): void;
    public function exportData(/* dataExporter */): void;
}
