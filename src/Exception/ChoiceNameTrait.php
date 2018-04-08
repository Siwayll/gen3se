<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception;

/**
 * Trait ChoiceNameTrait
 * @package Gen3se\Engine\Exception
 */
trait ChoiceNameTrait
{
    /**
     * @var string
     */
    protected $choiceName = '{choiceName}';

    /**
     * @param string $choiceName
     * @return void
     */
    final public function setChoiceName(string $choiceName): void
    {
        $this->choiceName = $choiceName;
    }
}
