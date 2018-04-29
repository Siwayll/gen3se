<?php declare(strict_types = 1);

namespace Gen3se\Engine\Bible;

use Gen3se\Engine\Bible;
use Gen3se\Engine\Choice;
use Gen3se\Engine\Result\Sample;
use Gen3se\Engine\Scenario as Scenario;
use Gen3se\Engine\Step\Resolve;
use Siwayll\RumData\RumData;

class Simple implements Bible
{
    private $choiceList = [];
    private $result;

    public function __construct(Choice ...$choices)
    {
        $this->result = new Sample();
        foreach ($choices as $choice) {
            $this->add($choice);
        }
    }

    private function add(Choice $choice): self
    {
        $this->choiceList[$choice->getName()] = $choice;
        return $this;
    }

    private function findChoice(string $choiceName): Choice
    {
        if (!isset($this->choiceList[$choiceName])) {
            echo 'ok';
        }

        return $this->choiceList[$choiceName];
    }

    public function play(Scenario $scenario): RumData
    {
        $scenario->read(
            function (string $choiceName) {
                $this
                    ->findChoice($choiceName)
                    ->treatsThis(
                        new Resolve($this->result)
                    )
                ;
            }
        );

        return $this->result->dump();
    }
}
