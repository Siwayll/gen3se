<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Result;

use Gen3se\Engine\Result;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Option as MockOptionProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Result\Filer as MockFilerProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\RumData\Converter\FromArray;

/**
 * @ignore
 */
class Sample extends Test
{
    use MockFilerProvider;
    use MockOptionProvider;

    public function shouldImplementResultInterface(): void
    {
        $this
            ->skip('rework in progress')
            ->testedClass
                ->hasInterface(Result::class)
        ;
    }

    public function shouldRegisterOptionData()
    {
        $this
            ->skip('rework in progress')
            ->given(
                $filer = $this->createMockFiler('first', 'second'),
                $option = $this->createMockOption(
                    null,
                    null,
                    [
                        'key' => 'data',
                        'secondeKey' => \uniqid(),
                    ]
                ),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->castToArray($this->testedInstance->dump())
                ->castToArray['first']
                    ->castToArray['second']->isEqualTo($option->dataToArray())
            ->if($this->testedInstance->registersTo($option, $filer))
            ->and($this->testedInstance->registersTo($option, $filer))
            ->castToArray($this->testedInstance->dump()['first']['second'])
                ->size->isEqualTo(3)
        ;
    }

    public function shouldNotRegisterEmptyData(): void
    {
        $this
            ->skip('rework in progress')
            ->given(
                $option = $this->createMockOption(),
                $filer = $this->createMockFiler(),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->mock($option)
                ->call('dataToArray')->once()
            ->castToArray($this->testedInstance->dump())
                ->isEmpty()
        ;
    }

    public function shouldCreateArrayForMultipleChoiceResults()
    {
        $this
            ->skip('rework in progress')
            ->given(
                $filer = $this->createMockFiler(),
                $option = $this->createMockOption(
                    null,
                    null,
                    ['text' => \uniqid()]
                ),
                $anotherOption = $this->createMockOption(
                    null,
                    null,
                    ['text' => \uniqid()]
                ),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->and($this->testedInstance->registersTo($anotherOption, $filer))
            ->castToArray($this->testedInstance->dump())
                ->variable[0]->isEqualTo(FromArray::toRumData($option->dataToArray()))
                ->variable[1]->isEqualTo(FromArray::toRumData($anotherOption->dataToArray()))
                ->size->isEqualTo(2)
        ;
    }
}
