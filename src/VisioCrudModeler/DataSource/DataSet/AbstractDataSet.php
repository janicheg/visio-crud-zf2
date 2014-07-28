<?php
namespace VisioCrudModeler\DataSource\DataSet;

use VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface;

abstract class AbstractDataSet implements DataSetInterface
{

    /**
     * holds descriptor object
     *
     * @var DataSetDescriptorInterface
     */
    protected $descriptor = null;

    /**
     * (non-PHPdoc)
     * 
     * @see \VisioCrudModeler\DataSource\DataSet\DataSetInterface::descriptor()
     */
    public function descriptor()
    {
        return $this->descriptor;
    }
}