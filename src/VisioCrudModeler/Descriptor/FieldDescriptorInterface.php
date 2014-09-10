<?php
namespace VisioCrudModeler\Descriptor;

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

}