<?php declare(strict_types = 1);


use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Gen3se\Engine\Choice\Simple as Choice;
use Gen3se\Engine\Choice\Option\Simple as Option;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Scenario\Simple as Scenario;
use Gen3se\Engine\Bible\Simple as Bible;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var Bible
     */
    protected $bible;
    protected $scenario;
    protected $resultData = null;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the :arg1 bible
     */
    public function theBible($arg1)
    {
        $this->bible = new Bible(
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
    }

    /**
     * @When I play :scenarioName Scenario
     */
    public function iPlayScenario(string $scenarioName)
    {
        $this->resultData = $this->bible->play(new Scenario('cookie shape', 'cookie flavor', 'cookie word'));
    }

    /**
     * @Then I should have a biscuit
     */
    public function iShouldHaveABiscuit()
    {
        var_dump($this->resultData);
    }

}
