<?php
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Generator\Params;
use VisioCrudModeler\Generator\Config\Config;
use VisioCrudModeler\Generator\Strategy\WebGenerator;

/**
 * controller for handling console commands
 *
 * @author bweres01
 *        
 */
class WebController extends AbstractActionController
{

    /**
     * handles running generators
     */
    public function generateAction()
    {
        $params = Params::factory($this->getRequest(), $this->getServiceLocator()->get('config')['VisioCrudModeler']['params']);
        $params->setParam('config', new Config($this->getServiceLocator()->get('config')['VisioCrudModeler']));
        
        $generatorStrategy = new WebGenerator($params);
        $generatorStrategy->setServiceLocator($this->getServiceLocator());
        $generatorStrategy->generate();
    }
    
}