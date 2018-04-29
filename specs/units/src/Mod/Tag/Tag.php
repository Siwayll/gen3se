<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Tag;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Mod\InstructionInterface;
use Gen3se\Engine\Mod\Tag\Option\Data\Tag as OptionDataTag;
use Gen3se\Engine\Specs\Units\Provider\Choice\Option as MockOptionProvider;
use Gen3se\Engine\Specs\Units\Provider\Mod\Tag\DataTrait;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

/**
 * @ignore
 */
class Tag extends Test
{
//    use DataTrait;
//    use MockOptionProvider;
//    use SimpleChoiceTrait;

    /**
     * @tags Mod
     */
    public function shouldImplementModInterface()
    {
        $this
            ->given($this->newTestedInstance)
            ->testedClass
                ->hasInterface('Gen3se\Engine\Mod\ModInterface')
        ;
    }

    public function shouldWorkInPrepareStep()
    {
        $this
            ->testedClass
            ->hasInterface('Gen3se\Engine\Step\IsPrepareReady')
        ;
    }

    /**
     * @tags Mod AddTag
     */
    public function shouldGiveTheTagAddInstruction()
    {
        $this
            ->given($this->newTestedInstance())
            ->array($this->testedInstance->getInstructions())
                ->size->isEqualTo(1)
            ->class(\get_class($this->testedInstance->getInstructions()[0]))
                ->hasInterface(InstructionInterface::class)
        ;
    }

    public function shouldValidateTagToAdd()
    {
        $this
            ->skip('not implemented yet')
            ->skip('deport controls to TagData')
            ->given(
                $this->newTestedInstance()
            )
            ->boolean($this->testedInstance->validateAddTag('TAGNAME'))
                ->isTrue()
            ->boolean($this->testedInstance->validateAddTag(['TAGNAME']))
                ->isTrue()
            ->boolean($this->testedInstance->validateAddTag(['TAGNAME', 'SECONDTAG']))
                ->isTrue()

            ->exception(function () {
                $this->testedInstance->validateAddTag(new \stdClass());
            })
                ->isInstanceOf('\TypeError')

            ->exception(function () {
                $this->testedInstance->validateAddTag(['TAGNAME', new \stdClass()]);
            })
                ->isInstanceOf('\TypeError')
        ;
    }

    public function shouldAddTagToTagList()
    {
        $this
            ->given(
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->addTag('TAGNAME'))
            ->array($this->testedInstance->getTags())
                ->isEqualTo(['TAGNAME'])
            ->if($this->testedInstance->addTag('tagName'))
            ->array($this->testedInstance->getTags())
                ->isEqualTo(['TAGNAME'])
        ;
    }

    /**
     * @tags TagApply
     */
    public function shouldMultiplyWeightFromOptionWithSameTag()
    {
        $this
            ->skip('not implemented yet')
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME'),
                $optionWithTag = $this->createMockOption('option', 100),
                $optionWithTag->getMockController()->findData = function () {
                    return $this->createMockOptionData();
                },
                $optionWithTag->add(new OptionDataTag('TAGNAME', 2)),
                $anotherOptionWithTag = new Option('optWithTag', 420),
                $anotherOptionWithTag->add(new TagData('TAGNAME', 0.8)),
                $optionWithAnotherTag = new Option('optWithAnotherTag', 400),
                $optionWithAnotherTag->add(new TagData('NOTPRESENT', 5))
            )
            ->if($this->testedInstance->appliesTagModifications($optionWithTag))
            ->mock($optionWithTag)
                ->call('findData')
                    ->once()
            ->integer($optionWithTag->getWeight())
                ->isEqualTo(200)
            ->if($this->testedInstance->appliesTagModifications($anotherOptionWithTag))
            ->integer($anotherOptionWithTag->getWeight())
                ->isEqualTo(336)
            ->if($this->testedInstance->appliesTagModifications($optionWithAnotherTag))
            ->integer($optionWithAnotherTag->getWeight())
                ->isEqualTo(400)
        ;
    }

    public function shouldAlwaySetARoundedIntegerWeight()
    {
        $this
            ->skip('not implemented yet')
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME'),
                $optionWithTag = new Option('optWithTag', 10),
                $optionWithTag->add(new TagData('TAGNAME', 0.31))
            )
            ->if($this->testedInstance->appliesTagModifications($optionWithTag))
                ->integer($optionWithTag->getWeight())
                ->isEqualTo(4)
        ;
    }

    public function shouldAddWeightToAnOptionIfRequested()
    {
        $this
            ->skip('not implemented yet')
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME'),
                $optionWithTag = new Option('optWithTag', 100),
                $optionWithTag->add(new TagData('TAGNAME', '+2'))
            )
            ->if($this->testedInstance->appliesTagModifications($optionWithTag))
            ->integer($optionWithTag->getWeight())
                ->isEqualTo(102)
        ;
    }

    public function shouldSubstractWeightToAnOptionIfRequested()
    {
        $this
            ->skip('not implemented yet')
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME'),
                $optionWithTag = new Option('optWithTag', 100),
                $optionWithTag->add(new TagData('TAGNAME', '-2'))
            )
            ->if($this->testedInstance->appliesTagModifications($optionWithTag))
            ->integer($optionWithTag->getWeight())
                ->isEqualTo(98)
        ;
    }
}
