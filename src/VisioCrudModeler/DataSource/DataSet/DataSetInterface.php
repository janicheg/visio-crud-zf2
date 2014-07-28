<?php
namespace VisioCrudModeler\DataSource\DataSet;

use Zend\Db\Sql\Where;
use VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface;

interface DataSetInterface
{

    /**
     * returns DataSet descriptor which allows data set structure recognition
     *
     * @return DataSetDescriptorInterface
     */
    public function descriptor();

    /**
     * creates DataSet entry and returns its object
     *
     * @param DataSetInterface $dataSet            
     * @param array $data            
     * @return mixed
     */
    public function create($data = null);

    /**
     * reads DataSet entries and returns list of them
     *
     * @param DataSetInterface $dataSet            
     * @param Where $where            
     * @return mixed
     */
    public function read(Where $where = null);

    /**
     * updates DataSet entries and returns number of affected items
     *
     * @param DataSetInterface $dataSet            
     * @param unknown $data            
     * @param Where $where            
     * @return int
     */
    public function update($data, Where $where = null);

    /**
     * deletes DataSet entries and returns number of affected items
     *
     * @param DataSetInterface $dataSet            
     * @param Where $where            
     * @return int
     */
    public function delete(Where $where = null);
}
