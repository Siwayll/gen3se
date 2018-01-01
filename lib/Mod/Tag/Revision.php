<?php
declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag;

use Gen3se\Engine\Exception\Mod\Tag\RevisionIsInvalid;
use Gen3se\Engine\Exception\Mod\Tag\RevisionTypeInvalid;

class Revision
{
    const VALUE_VALIDATOR = '@^(?<symbol>[\+\-\*x])(?<number>[0-9\.]+)@';
    private $revision;
    private $weight;

    private $method;

    public function __construct($revisionValue, int $weight)
    {
        if (!is_string($revisionValue) && !is_numeric($revisionValue)) {
            throw new RevisionTypeInvalid($revisionValue);
        }

        $this->weight = $weight;
        $this->revision = (float) $revisionValue;
        $this->method = 'classic';

        if (is_string($revisionValue)
            && preg_match(self::VALUE_VALIDATOR, $revisionValue, $match) === 1
        ) {
            $this->method = $match['symbol'];
            $this->revision = (float) $match['number'];
            if ((string) $this->revision !== $match['number']) {
                throw new RevisionIsInvalid($revisionValue);
            }
        }
    }

    public function __invoke(): int
    {
        switch ($this->method) {
            case '+':
                return (int) ceil($this->weight + $this->revision);

            case '-':
                if ($this->weight - $this->revision < 0) {
                    return 0;
                }
                return (int) ceil($this->weight - $this->revision);

            default:
                return (int) ceil($this->weight * $this->revision);
        }
    }
}
