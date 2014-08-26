<?php
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Generator\Params;
use VisioCrudModeler\Generator\Strategy\ExecuteGenerator;
use Zend\Console\ColorInterface;

/**
 * controller for handling console commands
 *
 * @author bweres01
 *        
 */
class ConsoleController extends AbstractActionController
{

    /**
     * handles listing
     */
    public function listAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $console = $this->getServiceLocator()->get('console');
        if (! $console instanceof \Zend\Console\Adapter\AdapterInterface) {
            throw new \RuntimeException('Cannot obtain console adapter, this action is only available from console');
        }
        $console->writeLine('Currently available generators:', ColorInterface::BLUE);
        foreach ($config['VisioCrudModeler']['generators'] as $name => $data) {
            $console->writeLine();
            $console->write($name, ColorInterface::GREEN);
            if (! empty($data['description'])) {
                $console->writeLine(" - " . $data['description']);
            }
        }
    }

    /**
     * handles running generators
     */
    public function generateAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $console = $this->getServiceLocator()->get('console');
        if (! $console instanceof \Zend\Console\Adapter\AdapterInterface) {
            throw new \RuntimeException('Cannot obtain console adapter, this action is only available from console');
        }
        $params = Params::factory($this->getRequest(), $config['VisioCrudModeler']['params']);
        $generatorStrategy = new ExecuteGenerator($params);
        // TODO finish generators strategy
    }
}