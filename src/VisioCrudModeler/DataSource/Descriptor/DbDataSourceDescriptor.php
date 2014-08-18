<?php
namespace VisioCrudModeler\DataSource\Descriptor;

/**
 * descriptor for database sources
 *
 * @author bweres01
 *        
 * @method \VisioCrudModeler\DataSource\DbDataSource getDataSource
 */
class DbDataSourceDescriptor extends AbstractDataSourceDescriptor
{

    protected $tableTypes = array(
        'BASE TABLE' => 'table',
        'VIEW' => 'view'
    );

    protected $tablesDescriptionStatement = 'SELECT * FROM information_schema.TABLES it WHERE it.TABLE_SCHEMA = :database';

    protected $viewsDescriptionStatement = 'SELECT * FROM information_schema.VIEWS iv WHERE iv.TABLE_SCHEMA = :database';

    protected $columnsDescriptionStatement = 'SELECT * FROM information_schema.COLUMNS ic WHERE ic.TABLE_SCHEMA = :database';

    protected $referenceDescriptionStatement = 'SELECT * FROM information_schema.KEY_COLUMN_USAGE kcu WHERE kcu.TABLE_SCHEMA = :database and kcu.REFERENCED_COLUMN_NAME IS NOT NULL';
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor::describe()
     */
    protected function describe()
    {
        // TODO Auto-generated method stub
        if (! $this->definitionResolved) {
            $this->describeTables();
            $this->describeViews();
            $this->describeColumns();
            $this->describeReferences();
            $this->definitionResolved = true;
        }
        return $this;
    }

    /**
     * describes tables in database
     */
    protected function describeTables()
    {
        $result = $this->getDataSource()
            ->getAdapter()
            ->createStatement($this->tablesDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $tableDefinition = $this->createTableDefinition($row);
                $this->definition[$tableDefinition['name']] = $tableDefinition;
            }
        }
    }

    /**
     * creates tables definition
     *
     * @param array $informationSchemaRow            
     * @return array
     */
    protected function createTableDefinition(array $informationSchemaRow)
    {
        return array(
            'type' => $this->tableTypes[$informationSchemaRow['TABLE_TYPE']],
            'name' => $informationSchemaRow['TABLE_NAME'],
            'updateable' => true,
            'fields' => array()
        );
    }

    /**
     * describes views in database
     */
    protected function describeViews()
    {
        $result = $this->getDataSource()
            ->getAdapter()
            ->createStatement($this->viewsDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                if ($row['IS_UPDATEABLE'] == 'NO') {
                    $this->definition[$row['TABLE_NAME']]['updateable'] = false;
                }
            }
        }
    }

    /**
     * describes columns in database
     */
    protected function describeColumns()
    {
        $result = $this->getDataSource()
            ->getAdapter()
            ->createStatement($this->columnsDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $fieldDescription = array(
                    'name' => $row['COLUMN_NAME'],
                    'type' => $row['DATA_TYPE'],
                    'character_maximum_length' => $row['CHARACTER_MAXIMUM_LENGTH'],
                    'numeric_precision' => $row['NUMERIC_PRECISION'],
                    'numeric_scale' => $row['NUMERIC_SCALE'],
                    'null' => ($row['IS_NULLABLE'] == 'YES') ? true : false,
                    'default' => $row['COLUMN_DEFAULT'],
                    'key' => $row['COLUMN_KEY'],
                    'reference' => false
                );
                $this->definition[$row['TABLE_NAME']]['fields'][$row['COLUMN_NAME']] = $fieldDescription;
            }
        }
    }

    /**
     * describes columns in database
     */
    protected function describeReferences()
    {
        $result = $this->getDataSource()
            ->getAdapter()
            ->createStatement($this->referenceDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $referenceDescription = array(
                    'dataset' => $row['REFERENCED_TABLE_NAME'],
                    'field' => $row['REFERENCED_COLUMN_NAME']
                );
                $this->definition[$row['TABLE_NAME']]['fields'][$row['COLUMN_NAME']]['reference'] = $referenceDescription;
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::getDataSetDescriptor()
     */
    public function getDataSetDescriptor($dataSetName)
    {
        // TODO Auto-generated method stub
    }
}