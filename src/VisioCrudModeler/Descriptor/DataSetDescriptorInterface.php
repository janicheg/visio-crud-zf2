<?php
namespace VisioCrudModeler\Descriptor;

/**
 * interface for DataSet descriptors
 * 
 * @author bweres01
 *
 */
interface DataSetDescriptorInterface
{

    /**
     * returns name of DataSet
     *
     * @return string
     */
    public function getName();
    
    /**
     * returns type of DataSet
     * 
     * @return string
     */
    public function getType();

    /**
     * returns list of field names for DataSet
     */
    public function listFields();

    /**
     * returns field Descriptor
     *
     * @param FieldDescriptorInterface $fieldName            
     */
    public function getFieldDescriptor($fieldName);
    
    /**
     * return all fields
     * 
     * @return array
     */
    public function getFields();
    
}