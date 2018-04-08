<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Mod\Tag;

trait DataTrait
{
    protected function createMockAppendData(string $tagName = 'TAG', $revisionValue = 100)
    {
        $mock = new \mock\Gen3se\Engine\Mod\Tag\DataInterface();

        $mock->getMockController()->toArray = [];
        return $mock;
    }
}
