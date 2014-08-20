<?php
namespace VisioCrudModeler\Descriptor;

interface DataSetDescriptorInterface
{

    /**
     * returns name of DataSet
     *
     * @return string
     */
    public function getName();

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
}