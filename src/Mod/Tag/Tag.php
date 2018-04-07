<?php
declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Exception\Mod\Tag\TagMalformed;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Step\IsPrepareReady;
use Gen3se\Engine\Step\Prepare;

class Tag implements ModInterface, IsPrepareReady
{
    const ADD_FIELDNAME = 'tag.add';
    const TAG_FIELDNAME = 'tag';

    const TAGNAME_VALIDATOR = '@^[A-Za-z0-9-_]+$@';

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
        $formatedData = $this->arrayOrStringValue($value, __METHOD__);
        foreach ($formatedData as $tagName) {
            if ($this->validateTagname($tagName) === false) {
                return false;
            }
        }

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
        foreach (array_keys($value) as $tagName) {
            if ($this->validateTagname($tagName) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the tag name does not contain illegal char
     * #exception
     * if the string tag name not validate preg match with TAGNAME_VALIDATOR
     */
    private function validateTagname(string $tagName): bool
    {
        if (preg_match(self::TAGNAME_VALIDATOR, $tagName) === 1) {
            return true;
        }

        throw new TagMalformed($tagName);
    }

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

    /**
     * Check if mod is up for steps
     * * Prepare
     */
    public function isUpForStep(string $stepName): bool
    {
        if ($stepName === Prepare::STEP_NAME) {
            return true;
        }
        return false;
    }

    /**
     * Appli tag modificator on option weight's
     */
    public function execPrepare(Choice $choice): void
    {
        foreach ($choice->getOptionCollection()->each() as $option) {
            $this->appliesTagModifications($option);
        }
    }
}
