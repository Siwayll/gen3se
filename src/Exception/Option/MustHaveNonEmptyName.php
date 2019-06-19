<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Level;

class MustHaveNonEmptyName extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'Option must have a non-empty name',
            Level::ERROR
        );
    }
}
