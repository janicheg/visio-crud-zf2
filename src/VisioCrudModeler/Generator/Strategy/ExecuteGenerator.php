<?php
namespace VisioCrudModeler\Generator\Strategy;

use VisioCrudModeler\Generator\ParamsInterface;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * strategy class for running generators according to passed params
 *
 * @author bweres01
 *        
 */
class ExecuteGenerator implements ServiceManagerAwareInterface
{

    /**
     * holds Service Manager instance
     *
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * holds dependency injection container
     *
     * @var \Zend\Di\Di
     */
    protected $di = null;

    /**
     * holds passed params object
     *
     * @var ParamsInterface
     */
    protected $params = null;

    /**
     * constructor, takes generator params interface
     *
     * @param ParamsInterface $params            
     */
    public function __construct(ParamsInterface $params)
    {
        $this->setParams($params);
    }

    /**
     * returns generator dependency resolver
     *
     * @return \VisioCrudModeler\Generator\Dependency
     */
    public function dependency()
    {
        return $this->getDi()->get('\VisioCrudModeler\Generator\Dependency', array(
            'dependency' => $this->params->getParam('config')['dependency']
        ));
    }

    /**
     * sets Params
     *
     * @param ParamsInterface $params            
     * @return ExecuteGenerator
     */
    public function setParams(ParamsInterface $params)
    {
        if ($params->getParam('di') instanceof \Zend\Di\Di) {
            $this->di = $params->getParam('di');
        }
        $this->params = $params;
        return $this;
    }

    /**
     * gets Params
     *
     * @return ParamsInterface
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * returns Dependency Injection container
     *
     * @return \Zend\Di\Di
     */
    public function getDi()
    {
        if (! $this->di instanceof Di) {
            $this->di = new Di();
        }
        return $this->di;
    }

    /**
     * sets Dependency Injection container
     *
     * @param Di $di            
     * @return ExecuteGenerator
     */
    public function setDi(Di $di)
    {
        $this->di = $di;
        return $this;
    }
    /*
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceManagerAwareInterface::setServiceManager()
     */
    public function setServiceManager(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        // TODO Auto-generated method stub
    }
}