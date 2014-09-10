<?php
namespace VisioCrudModeler\Descriptor\Web;

use VisioCrudModeler\Descriptor\AbstractDataSourceDescriptor;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;
/**
 * database DataSet descriptor
 *
 * @author bweres01
 *        
 */
class WebDataSourceDescriptor extends AbstractDataSourceDescriptor implements ListGeneratorInterface
{

    /**
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface 
     */
    protected $params;
    
    public function __construct(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        $this->dataSetDescriptors = new \ArrayObject(array());
    }
    
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor::describe()
     */
    protected function describe()
    {
        if (! $this->definitionResolved) {
           $this->describeTables();
           $this->describeColumns();
        }
        return $this;
        
    }
    
    /**
     * Describes tables in database
     */
    protected function describeTables()
    {
        $modelerParams = $this->params->getParam('modeler');
        foreach($modelerParams['tables'] as $param){
            $this->definition[$param['name']] =  array(
                'name' => $param['name'],
                'type' => 'table',
                'updateable' => 0,
                'fields' => array()
            );
        }
    }
    
    /**
     * describes columns in database
     */
    protected function describeColumns()
    {
        $modelerParams = $this->params->getParam('modeler');
        
        foreach ($modelerParams['elements'] as $element) {
            $fieldDescription = array(
                'name' => $element['name'],
                'type' => $element['type'],
                //'character_maximum_length' => $row['CHARACTER_MAXIMUM_LENGTH'],
                //'numeric_precision' => $row['NUMERIC_PRECISION'],
                //'numeric_scale' => $row['NUMERIC_SCALE'],
                //'null' => ($row['IS_NULLABLE'] == 'YES') ? true : false,
                //'default' => $row['COLUMN_DEFAULT'],
                'key' => null,
                //'reference' => false
                'label' => $element['label'],
                'validators' => $element['validators'],
                'filters' => $element['filters']
            );
            $this->definition[$element['table']]['fields'][$element['name']] = $fieldDescription;
        }
        
    }

    /**
     * returns DataSet descriptor
     *
     * @throws \VisioCrudModeler\Exception\DataSetNotFound
     * @return \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor
     */
    public function getDataSetDescriptor($dataSetName)
    {
        $this->describe();
        if (! array_key_exists($dataSetName, $this->definition)) {
            throw new DataSetNotFound("dataSet '" . $dataSetName . "' don't exists in '" . $this->name . "'");
        }
        if (! $this->dataSetDescriptors->offsetExists($dataSetName)) {
            $this->dataSetDescriptors->offsetSet($dataSetName, new WebDataSetDescriptor($this, $this->definition[$dataSetName]));
        }
        return $this->dataSetDescriptors->offsetGet($dataSetName);
    }

}