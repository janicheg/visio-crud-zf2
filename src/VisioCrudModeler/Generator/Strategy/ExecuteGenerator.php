<?php
namespace VisioCrudModeler\Generator\Strategy;

use VisioCrudModeler\Generator\ParamsInterface;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VisioCrudModeler\Generator\Dependency;
use VisioCrudModeler\Descriptor\DataSourceDescriptorInterface;

/**
 * strategy class for running generators according to passed params
 *
 * @author bweres01
 *        
 */
class ExecuteGenerator implements ServiceLocatorAwareInterface
{

    /**
     * holds service locator instance
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

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
    public function __construct(ParamsInterface $params = null)
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
        return new Dependency($this->params->getParam('config')['dependency']);
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
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    /*
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * runs specified generators
     */
    public function generate()
    {
        // setting up params
        $this->printParams();
        $this->params->setParam('di', $this->getDi());
        $this->params->setParam('descriptor', $this->getDescriptor());
        
        $dependency = $this->dependency();
        foreach ($dependency->dependencyListFor($this->params->getParam('generator')) as $name) {
            $this->console("\n" . 'Running generator: ' . $name);
            $this->getGenerator($name)->generate($this->params);
        }
    }

    /**
     * prints startup params
     */
    protected function printParams()
    {
        $this->console('Launch parameters');
        $displayKeys = [
            'author',
            'copyright',
            'project',
            'license',
            'modulesDirectory',
            'moduleName',
            'adapterServiceKey',
            'descriptor',
            'generator'
        ];
        foreach ($this->params->getParams() as $name => $value) {
            if (! empty($name) && in_array($name, $displayKeys)) {
                if (is_object($value)) {
                    $value = get_class($value);
                }
                $this->console($name . ': ' . $value);
            }
        }
    }

    /**
     * proxy method for writing to console
     *
     * @param string $message            
     */
    protected function console($message)
    {
        if ($this->params->getParam('console') instanceof \Zend\Console\Adapter\AdapterInterface) {
            $this->params->getParam('console')->writeLine($message);
        }
    }

    /**
     * returns generator instance
     *
     * @param string $generatorName            
     * @return \VisioCrudModeler\Generator\GeneratorInterface
     */
    public function getGenerator($generatorName)
    {
        $generators = $this->params->getParam('config')->get('generators');
        return $this->getDi()->get($generators[$generatorName]['adapter']);
    }

    /**
     * returns descriptor instance
     *
     * @return \VisioCrudModeler\Descriptor\DataSourceDescriptorInterface
     */
    public function getDescriptor()
    {
        $adapter = $this->getServiceLocator()->get($this->params->getParam('adapterServiceKey'));
        $descriptors = $this->params->getParam('config')->get('descriptors');
        return $this->getDi()->get($descriptors[$this->params->getParam('descriptor')]['adapter'], array(
            'adapter' => $adapter
        ));
    }
}