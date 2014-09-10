<?php

namespace VisioCrudModeler\Modeler;

class Modeler
{

    protected $moduleConfig;
    
    protected $tables;
    
    protected $hasConfiguration = false;
    
    
    /**
     * 
     * @param \VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor $dataSourceDescriptor
     */
    public function __construct($dataSourceDescriptor)
    {
        $dbName = $dataSourceDescriptor->getName();
        if(!isset($_COOKIE[$dbName])){
            return;
        }
        
        $configuration = json_decode($_COOKIE[$dbName], true);
        
        
        
        
        $res = json_decode($_COOKIE[$dataSourceDescriptor->getName()], true);
        
        
    }
    
    
    public function getModuleConfig()
    {
        return $this->moduleConfig;
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function setModuleConfig($moduleConfig)
    {
        $this->moduleConfig = $moduleConfig;
    }

    public function setTables($tables)
    {
        $this->tables = $tables;
    }


    
}
