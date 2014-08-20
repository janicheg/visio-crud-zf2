<?php
namespace VisioCrudModeler\Descriptor;

/**
 *
 * @author bweres01
 *        
 */
interface DataSourceDescriptorInterface
{

    /**
     * sets name of DataSource to describe
     *
     * @param string $name            
     */
    public function setName($name);

    /**
     * returns DataSource name to which this decriptor is linked
     *
     * @return string
     */
    public function getName();

    /**
     * returns list of DataSet names in described DataSource
     *
     * @return string[]
     */
    public function listDataSets();

    /**
     * returns given DataSet descriptor
     *
     * @param string $dataSetName            
     * @return DataSetDescriptorInterface
     */
    public function getDataSetDescriptor($dataSetName);
}