<?php
namespace VisioCrudModeler\Descriptor\Db;

use VisioCrudModeler\Descriptor\AbstractDataSetDescriptor;
use VisioCrudModeler\Exception\FieldNotFound;
use VisioCrudModeler\Exception\InformationNotFound;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;

/**
 * database DataSet descriptor
 *
 * @author bweres01
 *        
 */
class DbDataSetDescriptor extends AbstractDataSetDescriptor implements ListGeneratorInterface
{

    const UPDATEABLE = 'updateable';

    /**
     * holds intances of dataset field descriptors
     *
     * @var \ArrayObject
     */
    protected $fieldDescriptors = null;

    /**
     * constructor, accepts datasource and dataset definition
     *
     * @param \Zend\Db\Adapter\Adapter $dataSource            
     * @param array $definition            
     */
    public function __construct(DbDataSourceDescriptor $descriptor, array $definition)
    {
        $this->setDataSourceDescriptor($descriptor);
        $this->definition = $definition;
        $this->fieldDescriptors = new \ArrayObject(array());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::getName()
     */
    public function getName()
    {
        return $this->definition['name'];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::getType()
     */
    public function getType()
    {
        return $this->definition['type'];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::listFields()
     */
    public function listFields()
    {
        return array_keys($this->definition['fields']);
    }

    /**
     * returns field descriptor
     *
     * @throws \VisioCrudModeler\Exception\FieldNotFound
     * @return DbFieldDescriptor
     */
    public function getFieldDescriptor($fieldName)
    {
        // TODO Auto-generated method stub
        if (! array_key_exists($fieldName, $this->definition['fields'])) {
            throw new FieldNotFound("field '" . $fieldName . "' not found in '" . $this->getName() . "'");
        }
        if (! $this->fieldDescriptors->offsetExists($fieldName)) {
            $this->fieldDescriptors->offsetSet($fieldName, new DbFieldDescriptor($this, $this->definition['fields'][$fieldName]));
        }
        return $this->fieldDescriptors->offsetGet($fieldName);
    }

    /**
     * returns additional info about dataset
     *
     * if name is not provided the returns whole definition
     *
     * @param string $name            
     * @return boolean
     */
    public function info($name = null)
    {
        if (is_null($name)) {
            return $this->definition;
        }
        if (! array_key_exists($name, $this->definition)) {
            throw new InformationNotFound("definition information part '" . $name . "' not found");
        }
        switch ($name) {
            default:
                return $this->definition[$name];
                break;
        }
    }

    /**
     * list generator
     *
     * key are Field names, value is DbFieldDescriptor objects
     *
     * @return Generator
     */
    public function listGenerator()
    {
        foreach ($this->listFields() as $fieldName) {
            yield $fieldName => $this->getFieldDescriptor($fieldName);
        }
    }
}