<?php
namespace Gen3se\Engine\Choice;

/**
 * Prepares Choice for its resolution
 */
class Preparer
{
    private $choice;

    public function __construct(Choice $choice)
    {
        $this->choice = clone $choice;
    }

    /**
     * Get the prepared choice
     */
    public function __invoke(): Choice
    {
        return $this->choice;
    }
}
