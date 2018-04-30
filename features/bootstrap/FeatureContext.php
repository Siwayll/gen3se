<?php declare(strict_types = 1);


use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Gen3se\Engine\Bible\Simple as Bible;
use atoum\asserter;

require_once __DIR__ . '/../../vendor/atoum/atoum/classes/autoloader.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private const BIBLE_PATH = __DIR__ . '/../library/bible/%s.php';
    private const SCENARIO_PATH = __DIR__ . '/../library/scenario/%s.php';

    private $assert;

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
        $this->assert = new asserter\generator();
    }

    /**
     * @Given the :bibleName bible
     */
    public function theBible($bibleName)
    {
        $this->bible = include \sprintf(self::BIBLE_PATH, $bibleName);
    }

    /**
     * @When I play :scenarioName Scenario
     */
    public function iPlayScenario(string $scenarioName)
    {
        $scenario = include \sprintf(self::SCENARIO_PATH, $scenarioName);
        $this->resultData = $this->bible->play($scenario);
    }

    /**
     * @Then I should have a :depth value
     */
    public function iShouldHaveABiscuit(string $depth)
    {
        $this->assert
            ->castToArray($this->resultData)
                ->hasKey($depth)
                ->object[$depth]->isNotNull()
        ;
    }

}
