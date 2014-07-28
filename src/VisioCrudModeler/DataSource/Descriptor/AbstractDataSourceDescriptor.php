<?php
namespace VisioCrudModeler\DataSource\Descriptor;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VisioCrudModeler\DataSource\DataSourceInterface;
use VisioCrudModeler\DataSource\DataSourceAwareInterface;
use Zend\EventManager\EventManager;

abstract class AbstractDataSourceDescriptor implements DataSourceDescriptorInterface, EventManagerAwareInterface, ServiceLocatorAwareInterface, DataSourceAwareInterface
{

    /**
     * holds event manager instance
     *
     * @var EventManagerInterface
     */
    protected $eventManager = null;

    /**
     * holds service locator instance
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * holds definition of availbale data sets
     *
     * @var array
     */
    protected $definition = array();

    /**
     * holds flag for status of definition resolve
     *
     * @var boolean
     */
    protected $definitionResolved = false;

    /**
     * holds used data source
     *
     * @var DataSourceInterface
     */
    protected $dataSource = null;

    /**
     * holds name of data source
     *
     * @var string
     */
    protected $name = null;

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::setName()
     */
    public function setName($dataSourceName)
    {
        if ($dataSourceName != $this->name) {
            $this->resetDescribedData();
        }
        $this->name = $dataSourceName;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::listDataSets()
     */
    public function listDataSets()
    {
        if (! $this->definitionResolved) {
            $this->describe();
        }
        return array_keys($this->definition);
    }

    /**
     * resolves data set definitions
     */
    abstract protected function describe()
    {}

    /**
     * resets resolved DataSource description data
     *
     * @return \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor
     */
    protected function resetDescribedData()
    {
        $this->definition = array();
        $this->definitionResolved = false;
        return $this;
    }

    /**
     * sets event manager instance
     *
     * @param EventManagerInterface $eventManager            
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * gets event manager instance, lazy-loads if none was set
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (! $this->eventManager instanceof EventManagerInterface) {
            $this->eventManager = new EventManager(__CLASS__);
        }
        return $this->eventManager;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * sets DataSource instance
     *
     * @param DataSourceInterface $dataSource            
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        if ($dataSource != $this->dataSource) {
            $this->resetDescribedData();
        }
        $this->dataSource = $dataSource;
    }

    /**
     * returns DataSource instance
     *
     * @return DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
}