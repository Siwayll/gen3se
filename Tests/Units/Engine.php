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
use \Siwayll\Histoire\Loader\Simple;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Siwayll\Histoire\Order;
use Siwayll\Histoire\Constraint\Rule;
use Siwayll\Histoire\Result;
use Solire\Conf\Conf;
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

        $data = new Conf();
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
