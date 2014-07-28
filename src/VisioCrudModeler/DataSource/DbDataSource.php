<?php
namespace VisioCrudModeler\DataSource;

use Zend\Db\Adapter\Adapter;
use VisioCrudModeler\DataSource\DataSet\DataSetInterface;
use Zend\Db\Sql\Where;
/**
 * Database DataSource
 * 
 * @author bweres01
 *
 * @method \Zend\Db\Adapter\Adapter getAdapter()
 */
class DbDataSource extends AbstractDataSource
{

    /**
     * constructor takes database adapter and dataSource Name (ie. database name)
     * 
     * @param Adapter $adapter
     * @param unknown $dataSourceName
     */
    public function __construct(Adapter $adapter, $dataSourceName)
    {
        $this->adapter = $adapter;
        $this->setName($dataSourceName);
    }
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceInterface::getDataSet()
     */
    public function getDataSet($dataSetName)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceInterface::create()
     */
    public function create(DataSetInterface $dataSet, $data = null)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceInterface::read()
     */
    public function read(DataSetInterface $dataSet, Where $where = null)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceInterface::update()
     */
    public function update(DataSetInterface $dataSet, $data, Where $where = null)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\DataSourceInterface::delete()
     */
    public function delete(DataSetInterface $dataSet, Where $where = null)
    {
        // TODO Auto-generated method stub
    }
}