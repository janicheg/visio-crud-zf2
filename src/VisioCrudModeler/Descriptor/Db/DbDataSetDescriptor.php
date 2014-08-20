<?php
namespace VisioCrudModeler\Descriptor\Db;

use VisioCrudModeler\DataSource\DbDataSource;
use VisioCrudModeler\Descriptor\AbstractDataSetDescriptor;
use Zend\Db\Adapter\Adapter;

/**
 * database DataSet descriptor
 *
 * @author bweres01
 *        
 */
class DbDataSetDescriptor extends AbstractDataSetDescriptor
{

    /**
     * constructor, accepts datasource and dataset definition
     *
     * @param DbDataSource $dataSource            
     * @param array $definition            
     */
    public function __construct(Adapter $adapter, array $definition)
    {
        $this->adapter = $adapter;
        $this->setDefinition($definition);
    }

    /**
     * sets definition for data set
     *
     * @param array $definition            
     * @return DbDataSetDescriptor
     */
    public function setDefinition(array $definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * gets definition for data set
     *
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\AbstractDataSetDescriptor::getFieldDescriptor()
     */
    public function getFieldDescriptor($fieldName)
    {
        // TODO Auto-generated method stub
    }
}