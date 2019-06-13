<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Mod\Append;

trait AddTag
{
    protected function createMockAppendData(string ...$tags)
    {
        $mock = new \mock\Gen3se\Engine\Mod\Tag\Data\AddTag();

        $mock->getMockController()->toArray = [];
        $mock->getMockController()->getTagsToAdd = $tags;
        return $mock;
    }
}
