<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Level;

class MustHaveNonEmptyName extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'Choice must have a non-empty name',
            Level::ERROR
        );
    }
}
