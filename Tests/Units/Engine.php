<?php
/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */

namespace tests\unit\Siwayll\Histoire;

use atoum;
use Siwayll\Histoire\Constraint;
use Siwayll\Histoire\Constraint\Rule\Value;
use \Siwayll\Histoire\Engine as TestedClass;
use Siwayll\Histoire\Error\Level;
use \Siwayll\Histoire\Loader\Simple;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Siwayll\Histoire\Modificator\Tag;
use Siwayll\Histoire\Order;
use Siwayll\Histoire\Constraint\Rule;
use Siwayll\Histoire\Result;
use Siwayll\Histoire\Result\Core as ResultData;
use Symfony\Component\Yaml\Yaml;

/**
 *
 *
 * @author  Siwaÿll <sana.th.labs@gmail.com>
 * @license beerware http://wikipedia.org/wiki/Beerware
 */
class Engine extends atoum
{
    private function getLogger()
    {
        $logger = new Logger('testEngine');
        $logger->pushHandler(new Streamhandler(TEST_TMP_DIR . '/engine.log', Logger::CRITICAL));
        return $logger;
    }

    private function getLoader()
    {
        $data = [
            'order' => ['sexe'],
            'choices' => [Yaml::parse(file_get_contents(TEST_DATA_DIR . '/sexe.yml'))],
        ];
        $loader = new Simple($data);
        return $loader;
    }

    private function getResult()
    {
        $result = new Result();

        $data = new ResultData();
        $result->setStorage($data);
        return $result;
    }

    private function getOrder()
    {
        return new Order();
    }

    /**
     * Instantiation d'un choix
     *
     * @return void
     */
    public function testConstruct()
    {
        $loader = $this->getLoader();
        $order = $this->getOrder();
        $result = $this->getResult();
        $logger = $this->getLogger();
        $this
            ->given($engine = new TestedClass($loader, $order, $result, $logger))
            ->object($engine->getLoader())
                ->isIdenticalTo($loader)
            ->object($engine->getResult())
                ->isIdenticalTo($result)
            ->object($engine->getOrder())
                ->isIdenticalTo($order)
            ->object($engine->getLogger())
                ->isIdenticalTo($logger)
        ;
    }

    /**
     * Contrôle du setter & getter des mod
     *
     * @erturn void
     */
    public function testAddAndGetModificator()
    {
        $loader = $this->getLoader();
        $order = $this->getOrder();
        $result = $this->getResult();
        $logger = $this->getLogger();
        $this
            ->given($engine = new TestedClass($loader, $order, $result, $logger))
            ->exception(function () use ($engine) {
                $engine->getModificator('tag');
            })
                ->hasMessage('Modificator tag not present.')
                ->hasCode(Level::ERROR)
            ->given($tag = new Tag())
            ->object($engine->addModificator($tag))
                ->isIdenticalTo($engine)
            ->object($engine->getModificator('tag'))
                ->isIdenticalTo($tag)
            ->exception(function () use ($engine, $tag) {
                $engine->addModificator($tag);
            })
                ->hasMessage('Modificator tag already present.')
                ->hasCode(Level::WARNING)
        ;
    }

    public function testResolveAll()
    {
        $loader = $this->getLoader();
        $order = $this->getOrder();
        $result = $this->getResult();
        $logger = $this->getLogger();
        $this
            ->given($engine = new TestedClass($loader, $order, $result, $logger))
            ->if($order->addAtEnd('sexe'))
            ->object($engine->resolveAll())
                ->isIdenticalTo($engine)
            ->given($store = $engine->getResult()->getStorage())
            ->object($store->get('description'))
            ->string($store->get('description')->get('sexe'))
                ->isNotEmpty()
        ;
    }

    /**
     * Ajout de contraintes lors de la génération
     */
    public function testConstraint()
    {
        $loader = $this->getLoader();
        $order = $this->getOrder();
        $result = $this->getResult();
        $logger = $this->getLogger();

        $this
            ->given($engine = new TestedClass($loader, $order, $result, $logger))
            ->given($constraint = new Constraint())
            ->assert('Contrainte par valeur directe')
                ->if($constraint->setRuleTo('sexe', new Value('ANDROGYNE')))
                ->object($engine->addConstraint($constraint))
                    ->isIdenticalTo($engine)
                ->if($order->addAtEnd('sexe'))
                ->object($engine->resolveAll())
                    ->isIdenticalTo($engine)
                ->given($store = $engine->getResult()->getStorage())
                ->object($store->get('description'))
                ->string($store->get('description')->get('sexe'))
                    ->isEqualTo('androgyne')
        ;
    }
}
