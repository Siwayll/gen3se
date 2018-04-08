<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Mod\Instruction;
use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\Tag\Option\Data\AddTag;
use Gen3se\Engine\Step\IsPrepareReady;
use Gen3se\Engine\Step\Prepare;

class Tag implements ModInterface, IsPrepareReady
{
    const ADD_FIELDNAME = 'tag.add';
    const TAG_FIELDNAME = 'tag';


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


    public function validateAddTag(AddTag $tagAdd): bool
    {
        $tagAdd;
        return true;
    }

    public function addTag(AddTag $tagAdd)
    {
        foreach ($tagAdd->getTagsToAdd() as $tag) {
            $this->tags[$tag] = true;
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
     * Change _weight_ of the option if it has one or more _tags_
     * present in the TagList
     */
    public function appliesTagModifications(Option $option): void
    {
        /** @var DataInterface $tag */
        foreach ($option->findData(DataInterface::class) as $tag) {
            if (!isset($this->tags[$tag->getTagName()])) {
                continue;
            }

            $option->setWeight(
                (new Revision($tag->getRevisionValue(), $option->getWeight()))()
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
