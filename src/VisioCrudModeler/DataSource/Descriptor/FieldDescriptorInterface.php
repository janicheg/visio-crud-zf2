<?php
namespace VisioCrudModeler\DataSource\Descriptor;

interface FieldDescriptorInterface
{

    /**
     * returns name of the field
     *
     * @return string
     */
    public function getName();

    /**
     * returns type of the field
     *
     * @return string
     */
    public function getType();

    /**
     * return true if field references to another field in another data set
     *
     * @return boolean
     */
    public function isReference();
}