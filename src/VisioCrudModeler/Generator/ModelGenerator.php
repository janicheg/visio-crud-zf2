<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * generator class for creating Model classes and table gateways
 *
 * @author Jacek Pawelec jacek.pawelec@isobar.com
 *        
 */
class ModelGenerator implements GeneratorInterface
{
    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;
    
    /**
     * 
     * @var Zend\Filter\Word\UnderscoreToCamelCase 
     */
    protected $underscoreToCamelCase;
    
    /**
     *
     * @var string 
     */
    protected $baseModelParent = "\VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity";
    
    /**
     * holds list of "use" operator for base model
     * @var array 
     */
    protected $baseModelUses = array("Zend\EventManager\EventManagerInterface");
    
    protected $baseModelEventsMethods = array(
        'preInsert',
        'postInsert',
        'preUpdate',
        'postUpdate',
        'preDelete',
        'postDelete'
    );
    
    public function __construct()
    {
        $this->underscoreToCamelCase = new UnderscoreToCamelCase();
    }
    
    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        
        $descriptor = $this->params->getParam("descriptor");
        
        if (!($descriptor instanceof \VisioCrudModeler\Descriptor\ListGeneratorInterface)) {
            return;
        }
        
        foreach ($descriptor->listGenerator() as $name=>$dataSet) {
            $this->generateBaseModel($name, $dataSet);
            $this->generateModel($name);
            $this->generateTableGateway($name);
            
            $this->console(sprintf('writing generated model for "%s" table', $name));
        }
        
    }
    
    protected function generateTableGateway($name)
    {
        $camelName = $this->underscoreToCamelCase->filter($name);
        $className = $camelName . "Table";
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setExtendedClass("\VisioCrudModeler\Model\TableGateway\AbstractTable");
        
        $namespace = $this->params->getParam("moduleName") . "\Model";
        $class->setNamespaceName($namespace);
        
        $tableProperty = new \Zend\Code\Generator\PropertyGenerator();
        $tableProperty->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED)
            ->setName("table")
            ->setDefaultValue($name);
        $class->addPropertyFromGenerator($tableProperty);
        
        $arrayObjectPrototypeClassProperty = new \Zend\Code\Generator\PropertyGenerator();
        $arrayObjectPrototypeClassProperty->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED)
            ->setName("arrayObjectPrototypeClass")
            ->setDefaultValue("\\" . $namespace . "\\" . $camelName);
        $class->addPropertyFromGenerator($arrayObjectPrototypeClassProperty);
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('modelTable.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        
        $file = new FileGenerator();
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Model/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
    }
    
    /**
     * generates file with target model (if not exists yet)
     * @param string $name
     */
    protected function generateModel($name)
    {
        $className = $this->underscoreToCamelCase->filter($name);
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Model/" . $className . ".php";
        if (file_exists($modelClassFilePath)) {
            return;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $namespace = $this->params->getParam("moduleName") . "\Model";
        $class->setNamespaceName($namespace);
        $class->setExtendedClass("\\" . $namespace . "\BaseModel\Base" . $className);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($modelClassFilePath, $file->generate());
    }
    
    /**
     * generates code for base model and saves in file (overrides file if exists)
     * @param string $name
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateBaseModel($name, \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet)
    {
        $className = "Base" . $this->underscoreToCamelCase->filter($name);
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($this->params->getParam("moduleName") . "\Model\BaseModel");
        $class->setExtendedClass($this->baseModelParent);
        
        foreach ($this->baseModelUses as $use) {
            $class->addUse($use);
        }
        
        $this->addEventsMethods($class);
        
        foreach ($dataSet->listGenerator() as $column) {
            $this->generateColumnRelatedElements($class, $column);
        }
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
                ->setDocBlock($docBlock);
        
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Model/BaseModel/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
    }

    /**
     * generates propertiy, getter, setter and other methods...
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     */
    protected function generateColumnRelatedElements(ClassGenerator $class, \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column)
    {
        $name = preg_replace("/[^a-z0-9_]/i", "_", $column->getName());
        $name = $this->underscoreToCamelCase->filter($name);
        
        $property = $this->createProperty($column, $name);
        $class->addPropertyFromGenerator($property);
        
        $getMethod = $this->createGetMethod($column, $name);
        $class->addMethodFromGenerator($getMethod);
        
        $setMethod = $this->createSetMethod($column, $name);
        $class->addMethodFromGenerator($setMethod);
    }
    
    /**
     * generates empty methods for events to be overwriten in model
     * @param \Zend\Code\Generator\ClassGenerator $class
     */
    protected function addEventsMethods(ClassGenerator $class)
    {
        foreach ($this->baseModelEventsMethods as $methodName)
        {
            $method = new MethodGenerator();
            $method->setName($methodName);
            
            $parameter = new \Zend\Code\Generator\ParameterGenerator();
            $parameter->setName("eventManager");
            $parameter->setType("EventManagerInterface");
            $method->setParameter($parameter);
            
            $docblock = new \Zend\Code\Generator\DocblockGenerator($this->codeLibrary()->get('model.' . $methodName . '.description'));
            $docblock->setTag(array("name" => "param", "description" => "EventManagerInterface"));
            $method->setDocBlock($docblock);
            
            $class->addMethodFromGenerator($method);
        }
    }
    
    /**
     * generates get function for field
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     * @param string $propertyName
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function createGetMethod($column, $name)
    {
        $propertyName = lcfirst($name);
        $method = new MethodGenerator();
        $type = $this->getFieldType($column);
        $method->setBody(sprintf($this->codeLibrary()->get('model.getMethod' . ucfirst($type) . '.body'), $propertyName));
        $method->setName("get" . $name);
        
        $docblock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.getMethod.description'), $propertyName));
        $docblock->setTag(array("name" => "return", "description" => $type));
        $method->setDocBlock($docblock);
        
        return $method;
    }
    
    /**
     * 
     * @param string $propertyName
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function createSetMethod($column, $name)
    {
        $propertyName = lcfirst($name);
        $parameter = new \Zend\Code\Generator\ParameterGenerator();
        $parameter->setName("value");
        $method = new MethodGenerator();
        $method->setParameter($parameter);
        $type = $this->getFieldType($column);
        $method->setBody(sprintf($this->codeLibrary()->get('model.setMethod' . ucfirst($type) . '.body'), $propertyName));
        $method->setName("set" . $name);
        
        $docblock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.setMethod.description'), $propertyName));
        $docblock->setTag(array("name" => "param", "description" => $type));
        $method->setDocBlock($docblock);
        
        return $method;
    }
    
    /**
     * 
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     * @param string $name
     * @return \Zend\Code\Generator\PropertyGenerator 
     */
    protected function createProperty(\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column, $name)
    {
        $type = $this->getFieldType($column);
        $docblock = new \Zend\Code\Generator\DocblockGenerator('Column: ' . $column->getName());
        $docblock->setTag(array("name" => "var", "description" => $type));
        
        if ($column->getReferencedField()) {
            $reference = $column->getReferencedField();
            $docblock->setLongDescription("Reference to " . $column->referencedDataSetName() . "." . $reference->getName());
        }

        $property = new \Zend\Code\Generator\PropertyGenerator();
        $property->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PUBLIC);
        $property->setName(lcfirst($name));
        $property->setDocBlock($docblock);
        
        return $property;
    }
    
    /**
     * gets field type for PHP
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     * @return string
     */
    protected function getFieldType(\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column)
    {
        switch (strtolower($column->getType())) {
            case "int":
            case "integer":
            case "smallint":
            case "tinyint":
                return "int";
            case "number":
            case "double":
            case "real":
            case "float":
                return "float";
            case "bool":
            case "boolean":
                return "bool";
        }
        
        return "string";
    }
    
    /**
     * returns code library
     *
     * @return CodeLibrary
     */
    protected function codeLibrary()
    {
        return $this->params->getParam('di')->get('VisioCrudModeler\Generator\CodeLibrary');
    }

    /**
     * proxy method for writing to console
     *
     * @param string $message            
     */
    protected function console($message)
    {
        if ($this->params->getParam('console') instanceof \Zend\Console\Adapter\AdapterInterface) {
            $this->params->getParam('console')->writeLine($message);
        }
    }
    
    /**
     * returns absolute path to module directory
     *
     * @return string
     */
    protected function moduleRoot()
    {
        $modulesDirectory = $this->params->getParam('modulesDirectory');
        $moduleName = $this->params->getParam('moduleName');
        return $modulesDirectory . DIRECTORY_SEPARATOR . $moduleName;
    }
}