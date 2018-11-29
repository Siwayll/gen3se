<?php declare(strict_types = 1);

use Gen3se\Engine\Basic\Bible;
use Gen3se\Engine\Basic\Choice;
use Gen3se\Engine\Basic\Option;
use Gen3se\Engine\Basic\Panel;
use Gen3se\Engine\Choice\Name;

return new Bible(
    new Choice(
        new Name('cookie shape'),
        new Panel(
            new Option('square', 100),
            new Option('rectangular', 100),
            new Option('round', 50),
            new Option('oval', 50),
            new Option('star-shaped', 20)
        )
    ),
    new Choice(
        new Name('cookie flavor'),
        new Panel(
            new Option('plain', 100),
            new Option('chocolate', 100),
            new Option('sugar', 100),
            new Option('butter', 50),
            new Option('vanilla', 10)
        )
    ),
    new Choice(
        new Name('cookie word'),
        new Panel(
            new Option('cookie', 100),
            new Option('biscuit', 100)
        )
    )
);
