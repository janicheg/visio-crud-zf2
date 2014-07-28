<?php
namespace VisioCrudModeler\DataSource;

use VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface;

abstract class AbstractDataSource implements DataSourceInterface
{

    /**
     * holds descriptor class name
     *
     * @var string
     */
    protected $descriptorName = null;

    /**
     * holds descriptor instance
     *
     * @var DataSourceDescriptorInterface
     */
    protected $descriptor = null;

    /**
     * holds adapter object
     *
     * @var mixed
     */
    protected $adapter = null;

    /**
     * holds data source name
     *
     * @var name
     */
    protected $name = null;

    /**
     * sets name
     *
     * @param string $name            
     * @return AbstractDataSource
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->descriptor()->setName($name);
        return $this;
    }

    /**
     * gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\DataSourceInterface::descriptor()
     */
    public function descriptor()
    {
        if (! $this->descriptor instanceof DataSourceDescriptorInterface) {
            // @todo add Di loading of Descriptor class
        }
        return $this->descriptor;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\DataSourceInterface::getAdapter()
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}