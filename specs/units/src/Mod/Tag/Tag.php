<?php

namespace Gen3se\Engine\Specs\Units\Mod\Tag;

use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Mod\Tag\TagData;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Tag extends Test
{
    use SimpleChoiceTrait;

    public function shouldImplementModInterface()
    {
        $this
            ->given($this->newTestedInstance)
            ->testedClass
                ->hasInterface('Gen3se\Engine\Mod\ModInterface')
            ->testedClass
                ->hasConstant('ADD_FIELDNAME')
            ->testedClass
                ->hasConstant('TAG_FIELDNAME')
            ->string($this->testedInstance::ADD_FIELDNAME)
                ->isEqualTo('tag.add')
            ->string($this->testedInstance::TAG_FIELDNAME)
                ->isEqualTo('tag')
        ;
    }

    public function shouldGiveTheTagAddInstruction()
    {
        $this
            ->given($this->newTestedInstance())
            ->testedClass
                ->hasConstant('ADD_FIELDNAME')
            ->string($this->testedInstance::ADD_FIELDNAME)
                ->isEqualTo('tag.add')
            ->array($this->testedInstance->getInstructions())
                ->size->isEqualTo(1)
            ->class(get_class($this->testedInstance->getInstructions()[0]))
                ->hasInterface('Gen3se\Engine\Mod\InstructionInterface')
            ->string($this->testedInstance->getInstructions()[0]->getCode())
                ->isEqualTo($this->testedInstance::ADD_FIELDNAME)
        ;
    }

    public function shouldEnsureThatTheTagIsWellFormated()
    {
        $this
            ->given(
                $this->newTestedInstance()
            )
            ->boolean($this->testedInstance->validateAddTag('tagName'))
                ->isTrue()
            ->boolean($this->testedInstance->validateAddTag('TAG_NAME'))
                ->isTrue()
            ->boolean($this->testedInstance->validateAddTag('TAGNAME04'))
                ->isTrue()
            ->KapowException(
                function () {
                    $this->testedInstance->validateAddTag('créé rapidement');
                }
            )
                ->hasMessage('The tag "{tag}" is invalid in {optionName} in {choiceName}')
                ->hasKapowMessage('The tag "créé rapidement" is invalid in {optionName} in {choiceName}')
                ->hasCode(Level::ERROR)
            ->KapowException(
                function () {
                    $this->testedInstance->validateAddTag('TAG NAME');
                }
            )
                ->hasMessage('The tag "{tag}" is invalid in {optionName} in {choiceName}')
                ->hasKapowMessage('The tag "TAG NAME" is invalid in {optionName} in {choiceName}')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldValidateTagToAdd()
    {
        $this
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

    public function shouldMultiplyWeightFromOptionWithSameTag()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME'),
                $optionWithTag = new Option('optWithTag', 100),
                $optionWithTag->add(new TagData('TAGNAME', 2)),
                $anotherOptionWithTag = new Option('optWithTag', 420),
                $anotherOptionWithTag->add(new TagData('TAGNAME', 0.8)),
                $optionWithAnotherTag = new Option('optWithAnotherTag', 400),
                $optionWithAnotherTag->add(new TagData('NOTPRESENT', 5))
            )
            ->if($this->testedInstance->appliesTagModifications($optionWithTag))
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

    public function shouldControlTagFormat()
    {
        $this
            ->skip('This test should be in TagData')
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->addTag('TAGNAME')
            )
            ->exception(
                function () {
                    $optionWithTag = new Option('optWithTag', 10);
                    $optionWithTag->add(new TagData('', 3));
                    $this->testedInstance->appliesTagModifications($optionWithTag);
                }
            )
                ->isInstanceOf('\TypeError')
        ;
    }

    public function shouldAddWeightToAnOptionIfRequested()
    {
        $this
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

    public function shouldWorkInPrepareStep()
    {
        $this
            ->testedClass
                ->hasInterface('Gen3se\Engine\Step\IsPrepareReady')
        ;
    }
}
