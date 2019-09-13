<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Integration\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Gen3se\Engine\Basic\Bible;
use Gen3se\Engine\Basic\Option;
use Gen3se\Engine\Basic\Panel;
use Gen3se\Engine\Choice;
use Gen3se\Engine\Data\Next;
use Gen3se\Engine\Specs\Integration\AssertResultExporter;
use Gen3se\Engine\Specs\Integration\ControlledRandomizer;
use mageekguy\atoum\asserter;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private const BIBLE_PATH = __DIR__ . '/../library/bible/%s.php';
    private const SCENARIO_PATH = __DIR__ . '/../library/scenario/%s.php';

    private $assert;

    protected $bible = [];
    protected $scenario;
    private $randomizer;

    /** @var Choice\Option\Data[] */
    private $data = [];
    /** @var Choice[] */
    private $choices;
    /** @var string */
    private $lastChoiceName = '';
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
        $this->randomizer = new ControlledRandomizer();
    }

    /**
     * @Given the :bibleName bible
     */
    public function theBible(string $bibleName, TableNode $table): void
    {
        $bible = new Bible();
        foreach ($table as $row) {
            if (!isset($this->choices[$row['choiceName']])) {
                throw new \Exception(\sprintf('Choice %s not present', $row['choiceName']));
            }
            $this->choices[$row['choiceName']]->signUpTo($bible);
        }
        $this->bible[$bibleName] = $bible;
    }

    /**
     * @given the :choiceName Choice
     */
    public function givenChoice($choiceName, TableNode $table)
    {
        $this->lastChoiceName = $choiceName;
        $options = [];
        foreach ($table as $row) {
            $option = new Option($row['name'], (int) $row['weight']);
            if (isset($row['next']) && !empty($row['next'])) {
                $choiceNames = \explode(',', $row['next']);
                $option->add(new Next(...$choiceNames));
            }

            $options[] = $option;
        }
        $this->choices[$choiceName] = new \Gen3se\Engine\Basic\Choice(
            new Choice\Name($choiceName),
            new Panel(...$options)
        );
    }

    /**
     * @Given The randomizer blocked to :value
     */
    public function theRandomizerBlockedTo($value)
    {
        $this->randomizer->setReturnValue((int) $value);
    }
    /**
     * @when I resolve :choiceName
     */
    public function iResolveIt($choiceName): void
    {
        $this->pickChoice($choiceName)->resolve($this->randomizer);
    }

    /**
     * @when I resolve :choiceName :number times
     */
    public function iResolveTimes($choiceName, $number): void
    {
        $counter = 0;
        do {
            $this->pickChoice($choiceName)->resolve($this->randomizer);
            $counter++;
        } while ($counter < $number);
    }

    /**
     * @Then I should have :numberOfOptions Option on result export to :choiceName
     */
    public function iShouldHaveOption($numberOfOptions, $choiceName): void
    {
        $exporter = new AssertResultExporter();
        $this->pickChoice($choiceName)->exportResult($exporter);

        $this->assert->integer($exporter->countOptions())->isEqualTo($numberOfOptions);
    }

    /**
     * @Then I should have :value as result to :choiceName
     */
    public function iShouldHaveAsResultTo($value, $choiceName)
    {
        $exporter = new AssertResultExporter();
        $this->choices[$choiceName]->exportResult($exporter);

        $this->assert
            ->array($exporter->getOptionsData())
                ->array[0]
                    ->string['text']
                        ->isEqualTo($value)
        ;
    }

    /**
     * @Then I should have a right name when export data from :choiceName
     */
    public function iShouldHaveARightNameWhenExportDataFromColor($choiceName)
    {
        $exporter = new AssertResultExporter();
        $this->choices[$choiceName]->exportResult($exporter);

        $this->assert
            ->string($exporter->getChoiceNameAsString())
                ->isEqualTo($choiceName)
        ;
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

    /**
     * @Given The Data :name :value with code :dataCode
     */
    public function theTextDataWithCode($name, $value, $dataCode): void
    {
        $this->data[$dataCode] = new Choice\Option\Data\Simple($name, $value);
    }

    /**
     * @When I add the Data :dataCode to :choiceName
     */
    public function iAddTheDataTo($dataCode, $choiceName): void
    {
        $this->pickChoice($choiceName)->add($this->data[$dataCode]);
    }

    /**
     * @Then I should have :dataCode Data when export data from :choiceName
     */
    public function iShouldHaveDataWhenExportDataFrom($dataCode, $choiceName): void
    {
        $exporter = new AssertResultExporter();
        $this->pickChoice($choiceName)->exportResult($exporter);

        foreach ($exporter->getData() as $data) {
            if ($data === $this->data[$dataCode]) {
                return;
            }
        }

        throw new \Exception(\sprintf('"%s" is not present in the exported data of "%s"', $dataCode, $choiceName));
    }

    /**
     * @Then Exported :dataCode from :choiceName should have to value
     */
    public function exportedDataFromChoiceShouldHaveValue(
        string $dataCode,
        string $choiceName,
        PyStringNode $exportedValue
    ): void {
        $exporter = new AssertResultExporter();
        $this->pickChoice($choiceName)->exportResult($exporter);

        //throw new \Exception(\sprintf('"%s" is not present in the exported data of "%s"', $dataCode, $choiceName));
    }

    /**
     * @Given the :oracleCode Oracle who resolve :number times the :bibleCode Bible
     */
    public function theOracleWhoResolveTimesTheBible($oracleCode, $number, $bibleCode)
    {
        throw new \Gen3se\Engine\Specs\Integration\Context\PendingException();
    }

    /**
     * @When I ask the Oracle :oracleCode
     */
    public function iAskTheOracle($oracleCode)
    {
        throw new \Gen3se\Engine\Specs\Integration\Context\PendingException();
    }

    /**
     * @Then The Oracle :oracleCode should have :number :item
     */
    public function theOracleShouldHave($oracleCode, $number, $item)
    {
        throw new \Gen3se\Engine\Specs\Integration\Context\PendingException();
    }

    /**
     * Pick a Choice in already defined Choices
     * Could set 'it' as name to get the last declared Choice
     */
    private function pickChoice(string $choiceName): Choice
    {
        if ($choiceName === 'it') {
            $choiceName = $this->lastChoiceName;
        }

        if (!isset($this->choices[$choiceName])) {
            throw new \RuntimeException(\sprintf('The choice *%s* is not defined', $choiceName));
        }

        return $this->choices[$choiceName];
    }
}
