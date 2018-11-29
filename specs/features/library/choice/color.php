<?php declare(strict_types = 1);

use Gen3se\Engine\Basic;
use Gen3se\Engine\Choice;

return new Basic\Choice(
    new Choice\Name('color'),
    new Basic\Panel(
        new Basic\Option('red', 100),
        new Basic\Option('blue', 600),
        new Basic\Option('green', 100),
        new Basic\Option('purple', 100)
    )
);
