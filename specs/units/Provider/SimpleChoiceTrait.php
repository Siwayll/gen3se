<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

use Gen3se\Engine\Choice\Provider;
use Gen3se\Engine\Choice\Simple as Choice;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Collection as MockOptionCollectionProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Option\Data as MockOptionDataProvider;

trait SimpleChoiceTrait
{
    use MockOptionCollectionProvider;
    use MockOptionDataProvider;

    /**
     * Get a Choice without any special features
     */
    protected function getEyeColorChoice()
    {
        $optCollection = $this->createMockOptionCollection(4);
//        $optCollection->getMockController()
//        $optCollection->add(
//            (new Option('blue', 30))
//                ->add($this->createMockOptionData('bleu'))
//        );
//        $optCollection->add(
//            (new Option('green', 15))
//                ->add(new Option\Data\Text('vert'))
//        );
//        $optCollection->add(
//            (new Option('marron', 150))
//                ->add(new Option\Data\Text('marron'))
//        );
//        $optCollection->add(
//            (new Option('purple', 1))
//                ->add(new Option\Data\Text('violet'))
//        );

        $choice = new Choice('eyeColor', $optCollection);

        return $choice;
    }

    /**
     * Get a Choice without any special features
     */
    protected function getHairColorChoice()
    {
        $optCollection = $this->createMockOptionCollection(4);
//        $optCollection->add(
//            (new Option('noir', 300))
//                ->add(new Option\Data\Text('les cheveux noirs'))
//        );
//        $optCollection->add(
//            (new Option('blond', 100))
//                ->add(new Option\Data\Text('les cheveux blonds'))
//        );
//        $optCollection->add(
//            (new Option('vert', 5))
//                ->add(new Option\Data\Text('les cheveux verts'))
//        );
//        $optCollection->add(
//            (new Option('violet', 1))
//                ->add(new Option\Data\Text('les cheveux violets'))
//        );

        $choice = new Choice('cheveux', $optCollection);

        return $choice;
    }
}
