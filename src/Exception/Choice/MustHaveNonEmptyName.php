<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

/**
 * Class ChoiceMustHaveNonEmptyName
 * @package Gen3se\Engine\Exception
 */
class MustHaveNonEmptyName extends \Exception
{
    public $message = 'Choice must have a non-empty name';

    public $code = 400;

    /**
     * ChoiceMustHaveNonEmptyName constructor.
     */
    public function __construct()
    {
    }
}
