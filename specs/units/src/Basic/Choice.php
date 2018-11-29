<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Basic;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Choice\Resolved;
use Gen3se\Engine\Specs\Units\Choice as ChoiceContract;
use Gen3se\Engine\Specs\Units\Core\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Collection as MockOptionCollectionProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Data as MockChoiceDataProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Randomize as MockRandomizeProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Step as MockStepProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Gen3se\Engine\Step\PostResolve;
use Gen3se\Engine\Step\Prepare;
use Gen3se\Engine\Step\Resolve;
use Siwayll\Kapow\Level;

class Choice extends ChoiceContract
{
}
