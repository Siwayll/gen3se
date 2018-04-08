<?php declare(strict_types = 1);


use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Option\Simple as Option;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\DataExporter;
use Gen3se\Engine\Engine;
use Gen3se\Engine\Scenario;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected $choiceProvider;
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
        $this->scenario = new Scenario();
    }

    /**
     * @Given the :arg1 gen3rator
     */
    public function theGen3rator($arg1)
    {
        $this->choiceProvider = (new Provider())
            ->add(new Choice(
                'cookie shape',
                new Collection(
                    new Option('square', 100),
                    new Option('rectangular', 100),
                    new Option('round', 50),
                    new Option('oval', 50),
                    new Option('star-shaped', 20)
                )
            ))
            ->add(new Choice(
                'cookie flavor',
                new Collection(
                    new Option('plain', 100),
                    new Option('chocolate', 100),
                    new Option('sugar', 100),
                    new Option('butter', 50),
                    new Option('vanilla', 10)
                )
            ))
            ->add(new Choice(
                'cookie word',
                new Collection(
                    new Option('cookie', 100),
                    new Option('biscuit', 100)
                )
            ))
        ;

       $this->scenario
           ->append('cookie shape')
           ->append('cookie flavor')
           ->append('cookie word')
       ;
    }

    /**
     * @When I execute Gen3se
     */
    public function iExecuteGen3se()
    {
        $this->resultData = new DataExporter();
        (new Engine(
            $this->choiceProvider,
            $this->scenario,
            $this->resultData
        ))->run();
    }

    /**
     * @Then I should have a biscuit
     */
    public function iShouldHaveABiscuit()
    {
        var_dump($this->resultData);
    }

}
