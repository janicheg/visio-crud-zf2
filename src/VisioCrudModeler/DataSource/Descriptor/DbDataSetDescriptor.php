<?php
namespace VisioCrudModeler\DataSource\Descriptor;

class DbDataSetDescriptor extends AbstractDataSetDescriptor
{

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