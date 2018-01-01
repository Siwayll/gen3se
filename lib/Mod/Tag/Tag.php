<?php
declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag;

use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Option\Option;

class Tag implements ModInterface
{
    const ADD_FIELDNAME = 'tag.add';
    const TAG_FIELDNAME = 'tag';

    const TAGNAME_VALIDATOR = '@^[A-Z0-9-_]+$@';

    /**
     * TagList
     */
    private $tags = [];

    public function getInstructions(): array
    {
        return [
            new Instruction(
                self::ADD_FIELDNAME,
                [$this, 'validateAddTag'],
                [$this, 'addTag']
            )
        ];
    }

    /**
     * Accept string or array of strings and convert them into array of strings
     *
     * #exception
     * if type of $value is not a string or an array
     * if any row of the array is not a string
     */
    private function arrayOrStringValue($value, string $method): array
    {
        if (!is_string($value) && !is_array($value)) {
            throw new \TypeError(
                'Argument 1 passed to ' . $method . '() must be of the type string or array of strings, '
                . gettype($value) . ' given'
            );
        }

        if (is_array($value)) {
            foreach ($value as $row) {
                if (!is_string($row)) {
                    throw new \TypeError(
                        'Argument 1 passed to ' . $method . '() must be of the type string or array of strings, '
                        . 'array of ' . gettype($value) . ' given'
                    );
                }
            }
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        return $value;
    }

    /**
     * Get TagList
     */
    public function getTags(): array
    {
        return array_keys($this->tags);
    }


    public function validateAddTag($value): bool
    {
        $this->arrayOrStringValue($value, __METHOD__);

        return true;
    }

    public function addTag($value)
    {
        $tags = $this->arrayOrStringValue($value, __METHOD__);

        foreach ($tags as $tag) {
            $this->tags[strtoupper($tag)] = true;
        }
    }

    public function validateTagField(array $value): bool
    {
        foreach ($value as $tagName => $multiplier) {
        }

        return true;
    }
//
//    private function validateTagname(string $tagName): bool
//    {
//        if (preg_match(self::TAGNAME_VALIDATOR, $tagName) === 1) {
//            return true;
//        }
//        return false;
//    }

    /**
     * Change _weight_ of the option if it has one or more _tags_
     * present in the TagList
     */
    public function appliesTagModifications(Option $option): void
    {
        if (!$option->exists(self::TAG_FIELDNAME)) {
            return;
        }
        $data = $option->get(self::TAG_FIELDNAME);
        $this->validateTagField($data);

        foreach ($data as $tagName => $revisionValue) {
            if (!isset($this->tags[$tagName])) {
                continue;
            }

            $option->setWeight(
                (new Revision($revisionValue, $option->getWeight()))()
            );
        }
    }
}
