<?php declare(strict_types = 1);

use Gen3se\Engine\Bible\Simple as Bible;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option\Simple as Option;
use Gen3se\Engine\Choice\Simple as Choice;

return new Bible(
    new Choice(
        'cookie shape',
        new Collection(
            new Option('square', 100),
            new Option('rectangular', 100),
            new Option('round', 50),
            new Option('oval', 50),
            new Option('star-shaped', 20)
        )
    ),
    new Choice(
        'cookie flavor',
        new Collection(
            new Option('plain', 100),
            new Option('chocolate', 100),
            new Option('sugar', 100),
            new Option('butter', 50),
            new Option('vanilla', 10)
        )
    ),
    new Choice(
        'cookie word',
        new Collection(
            new Option('cookie', 100),
            new Option('biscuit', 100)
        )
    )
);
