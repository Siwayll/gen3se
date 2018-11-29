<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice;

class Name
{
    /** @var string */
    private $name;

    public function isValid(string $name): bool
    {
        if (empty($name)) {
            return false;
        }
        return true;
    }

    public function __construct(string $name)
    {
        if (!self::isValid($name)) {
            throw new \Exception(); // @todo improve that
        }
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
