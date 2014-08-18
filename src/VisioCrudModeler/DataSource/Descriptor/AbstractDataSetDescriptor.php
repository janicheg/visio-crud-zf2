<?php
namespace VisioCrudModeler\DataSource\Descriptor;

use VisioCrudModeler\DataSource\DataSourceAwareInterface;

abstract class AbstractDataSetDescriptor implements DataSetDescriptorInterface, DataSourceAwareInterface
{
    protected $fieldDescriptorName='';
    protected $fieldDescriptorPrototype=null;
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
    
    /**
     * sets field descriptor prototype
     *
     * @param FieldDescriptorInterface $prototype
     * @return DbDataSetDescriptor
     */
    public function setFieldDescriptorPrototype($prototype)
    {
        $this->fieldDescriptorPrototype = $prototype;
        return $this;
    }
    
    /**
     * gets field descriptor prototype
     *
     * @return FieldDescriptorInterface
     */
    public function getFieldDescriptorPrototype()
    {
        if (is_null($this->fieldDescriptorPrototype)) {
            $this->fieldDescriptorPrototype = new DbFieldDescriptor();
        }
        return $this->fieldDescriptorPrototype;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface::getFieldDescriptor()
     */
    abstract public function getFieldDescriptor($fieldName)
    {
        // TODO Auto-generated method stub
    }
}