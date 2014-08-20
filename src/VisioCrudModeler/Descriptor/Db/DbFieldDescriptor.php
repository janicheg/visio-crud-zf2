<?php
namespace VisioCrudModeler\Descriptor\Db;

use VisioCrudModeler\Descriptor\FieldDescriptorInterface;
use VisioCrudModeler\Descriptor\ReferenceFieldInterface;
use VisioCrudModeler\Exception\InformationNotFound;

/**
 * DataSet Field descriptor
 *
 * @author bweres01
 *        
 */
class DbFieldDescriptor implements FieldDescriptorInterface, ReferenceFieldInterface
{

    const NOT_NULL = 'null';

    const KEY = 'key';

    const DEFAULT_VALUE = 'default';

    const NUMERIC_SCALE = 'numeric_scale';

    const NUMERIC_PRECISION = 'numeric_precision';

    const CHARACTER_MAXIMUM_LENGTH = 'character_maximum_length';

    /**
     * holds field definition
     *
     * @var array
     */
    protected $definition = array();

    /**
     * holds DataSet Descriptor
     *
     * @var DbDataSetDescriptor
     */
    protected $dataSetdescriptor = null;

    /**
     * constructor, takes DataSet descriptor and field definition
     *
     * @param DbDataSetDescriptor $descriptor            
     * @param array $definition            
     */
    public function __construct(DbDataSetDescriptor $descriptor, array $definition)
    {
        $this->setDataSetDescriptor($descriptor);
        $this->definition = $definition;
    }

    /**
     * sets DataSet descriptor
     *
     * @param DbDataSetDescriptor $descriptor            
     * @return DbFieldDescriptor
     */
    public function setDataSetDescriptor($descriptor)
    {
        $this->dataSetdescriptor = $descriptor;
        return $this;
    }

    /**
     * gets DataSet descriptor
     *
     * @return DbDataSetDescriptor
     */
    public function getDataSetDescriptor()
    {
        return $this->dataSetdescriptor;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::getName()
     */
    public function getName()
    {
        return $this->definition['name'];
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::getType()
     */
    public function getType()
    {
        return $this->definition['type'];
    }

    /**
     * returns referenced field descriptor
     *
     * @return \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor
     */
    public function getReferencedField()
    {
        if ($this->isReference()) {
            if ($this->referencedDataSetName() == $this->getDataSetDescriptor()->getName()) {
                return $this->getDataSetDescriptor()->getFieldDescriptor($this->referencedFieldName());
            } else {
                return $this->getDataSetDescriptor()
                    ->getDataSourceDescriptor()
                    ->getDataSetDescriptor($this->referencedDataSetName())
                    ->getFieldDescriptor($this->referencedFieldName());
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::isReference()
     */
    public function isReference()
    {
        return ($this->definition['reference'] !== false);
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\ReferenceFieldInterface::referencedDataSetName()
     */
    public function referencedDataSetName()
    {
        return $this->definition['reference']['dataset'];
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\ReferenceFieldInterface::referencedFieldName()
     */
    public function referencedFieldName()
    {
        return $this->definition['reference']['field'];
    }

    /**
     * returns additional info about field
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
            case self::NOT_NULL:
                return ! $this->definition[self::NOT_NULL];
                break;
            default:
                return $this->definition[$name];
                break;
        }
    }
}