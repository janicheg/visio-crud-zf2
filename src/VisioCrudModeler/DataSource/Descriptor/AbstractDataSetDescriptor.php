<?php
namespace VisioCrudModeler\DataSource\Descriptor;

use VisioCrudModeler\DataSource\DataSourceAwareInterface;

abstract class AbstractDataSetDescriptor implements DataSetDescriptorInterface, DataSourceAwareInterface
{
    protected $fieldDescriptorName='';
    protected $definition=array();
    protected $definitionResolved=false;
    protected $name=null;
    protected $dataSource = null;
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceAwareInterface::setDataSource()
     */
    public function setDataSource(\VisioCrudModeler\DataSource\DataSourceInterface $dataSource)
    {
        $this->dataSource=$dataSource;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceAwareInterface::getDataSource()
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface::listFields()
     */
    public function listFields()
    {
        return array_keys($this->definition);
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface::getFieldDescriptor()
     */
    abstract public function getFieldDescriptor($fieldName)
    {
        // TODO Auto-generated method stub
    }
}