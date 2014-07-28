<?php
namespace VisioCrudModeler\DataSource;

use Zend\Db\Sql\Where;
use VisioCrudModeler\DataSource\DataSet\DataSetInterface;
use VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface;

interface DataSourceInterface
{

    /**
     * returns descriptor which allows DataSource structure recognition
     *
     * @return DataSourceDescriptorInterface
     */
    public function descriptor();

    /**
     * returns adapter used to communicate with specific data source
     *
     * @return mixed
     */
    public function getAdapter();

    /**
     * returns DataSet object
     *
     * @param string $dataSetName            
     * @return DataSetInterface
     */
    public function getDataSet($dataSetName);

    /**
     * creates DataSet entry and returns its object
     *
     * @param DataSetInterface $dataSet            
     * @param array $data            
     * @return mixed
     */
    public function create(DataSetInterface $dataSet, $data = null);

    /**
     * reads DataSet entries and returns list of them
     *
     * @param DataSetInterface $dataSet            
     * @param Where $where            
     * @return mixed
     */
    public function read(DataSetInterface $dataSet, Where $where = null);

    /**
     * updates DataSet entries and returns number of affected items
     *
     * @param DataSetInterface $dataSet            
     * @param unknown $data            
     * @param Where $where            
     * @return int
     */
    public function update(DataSetInterface $dataSet, $data, Where $where = null);

    /**
     * deletes DataSet entries and returns number of affected items
     *
     * @param DataSetInterface $dataSet            
     * @param Where $where            
     * @return int
     */
    public function delete(DataSetInterface $dataSet, Where $where = null);
}