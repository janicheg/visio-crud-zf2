<?php
namespace VisioCrudModeler\Descriptor;

/**
 * abstract class for DataSet Descriptor
 *
 * @author bweres01
 *        
 */
abstract class AbstractDataSetDescriptor implements DataSetDescriptorInterface
{

    protected $fieldDescriptorName = '';

    protected $fieldDescriptorPrototype = null;

    protected $definition = array();

    protected $definitionResolved = false;

    protected $name = null;

    protected $adapter = null;

    /**
     * sets adapter
     *
     * @param mixed $adapter            
     * @return AbstractDataSetDescriptor
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * gets adapter
     *
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->adapter;
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
    abstract public function getFieldDescriptor($fieldName);
}