<?php
namespace VisioCrudModeler\DataSource;

interface DataSourceAwareInterface
{
    /**
     * sets DataSource instance
     * 
     * @param DataSourceInterface $dataSource
     */
    public function setDataSource(DataSourceInterface $dataSource);
    /**
     * returns DataSource instance
     * 
     * @return DataSourceInterface
     */
    public function getDataSource();
}