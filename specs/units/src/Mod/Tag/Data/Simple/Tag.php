<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Tag\Option\Data\Simple;

use Gen3se\Engine\Choice\Option\Data;
use Gen3se\Engine\Specs\Units\Core\Test;
use Siwayll\Kapow\Level;

/**
 * @ignore
 */
class Tag extends Test
{
    public function shouldImplementOptionDataTagInterface()
    {
        $this
            ->testedClass
                ->hasInterface(Data::class)
                ->hasInterface(\Gen3se\Engine\Mod\Tag\Option\Data\Tag::class)
        ;
    }

    public function shouldReturnEmptyOnConvertToArray()
    {
        $this
            ->given(
                $this->newTestedInstance('TAGNAME', 200)
            )
            ->array($this->testedInstance->toArray())
                ->isEmpty()
        ;
    }

    public function shouldGiveTagName()
    {
        $this
            ->given(
                $this->newTestedInstance('TAGNAME', 200)
            )
            ->string($this->testedInstance->getTagName())
                ->isEqualTo('TAGNAME')
        ;
    }

    public function shouldGiveRevisionValue()
    {
        $this
            ->given(
                $this->newTestedInstance('TAGNAME', 200)
            )
            ->integer($this->testedInstance->getRevisionValue())
                ->isEqualTo(200)
        ;
    }

    public function shouldControlNameValidityOfTheTag()
    {
        $this
            ->object($this->newTestedInstance('TAGNAME04', 100))
            ->object($this->newTestedInstance('TAG_NAME', 100))
            ->KapowException(
                function () {
                    $this->newTestedInstance('tagname', 200);
                }
            )
                ->hasMessage('The tag "{tag}" is invalid in {optionName} in {choiceName}')
                ->hasKapowMessage('The tag "tagname" is invalid in {optionName} in {choiceName}')
                ->hasCode(Level::ERROR)
            ->KapowException(
                function () {
                    $this->newTestedInstance('créé rapidement', 200);
                }
            )
                ->hasMessage('The tag "{tag}" is invalid in {optionName} in {choiceName}')
                ->hasKapowMessage('The tag "créé rapidement" is invalid in {optionName} in {choiceName}')
                ->hasCode(Level::ERROR)
            ->KapowException(
                function () {
                    $this->newTestedInstance('TAG NAME', 100);
                }
            )
                ->hasMessage('The tag "{tag}" is invalid in {optionName} in {choiceName}')
                ->hasKapowMessage('The tag "TAG NAME" is invalid in {optionName} in {choiceName}')
                ->hasCode(Level::ERROR)
        ;
    }
}
