<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;
use VisioCrudModeler\Descriptor\FieldDescriptorInterface;
use VisioCrudModeler\Generator\Config\Config;

/**
 * generator class for creating Form and Grid classes
 *
 * @author Jacek Pawelec jacek.pawelec@isobar.com
 *        
 */
class FormGenerator implements GeneratorInterface
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
    protected $baseGridParent = "\ZfTable\AbstractTable";
    
    /**
     *
     * @var string 
     */
    protected $baseFormParent = "\VisioCrudModeler\Form\AbstractForm";
    
    /**
     *
     * @var array 
     */
    protected $baseGridUses = array();
    
    /**
     *
     * @var array 
     */
    protected $baseFormUses = array();
    
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
        
        $runtime = array();
        if ($params->getParam('runtimeConfiguration') instanceof Config) {
            $runtime = (array) $params->getParam('runtimeConfiguration')->get('form');
        }
        
        foreach ($descriptor->listGenerator() as $name=>$dataSet) {
            if (! array_key_exists($name, $runtime)) {
                $runtime[$name] = array();
            }
            $runtime[$name]['baseGrid'] = $this->generateBaseGrid($dataSet);
            $runtime[$name]['grid'] = $this->generateGrid($dataSet, $runtime[$name]['baseGrid']);
            
            $runtime[$name]['baseForm'] = $this->generateBaseForm($dataSet);
            $runtime[$name]['form'] = $this->generateForm($dataSet, $runtime[$name]['baseForm']);
            
            $this->console(sprintf('writing generated forms for "%s" table', $name));
        }
        
        return $runtime;
    }
    
    protected function generateForm(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Form";
        $namespace = $this->params->getParam("moduleName") . "\Form";
        $fullClassName = '\\' . $namespace . '\\' . $className;
        
        $gridClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Form/" . $className . ".php";
        if (file_exists($gridClassFilePath)) {
            return $fullClassName;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($extends);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('form.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($gridClassFilePath, $file->generate());
        return $fullClassName;
    }
    
    /**
     * generates code for forms and saves in file (overrides file if exists)
     * @param DataSetDescriptorInterface $dataSet
     * @return string
     */
    protected function generateBaseForm(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Form";
        $namespace = $this->params->getParam("moduleName") . "\Form\BaseForm";
        $fullClassName = '\\' . $namespace . '\\' . $className;
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($this->baseFormParent);
        
        foreach ($this->baseFormUses as $use) {
            $class->addUse($use);
        }
        
        $this->generateFormConstruct($class, $dataSet);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('form.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
                ->setDocBlock($docBlock);
        
        $formClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Form/BaseForm/" . $className . ".php";
        file_put_contents($formClassFilePath, $file->generate());
        return $fullClassName;
    }
    
    /**
     * generates init method
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function generateFormConstruct(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $method = new MethodGenerator("__contruct");
        $param = new \Zend\Code\Generator\ParameterGenerator("name");
        $param->setDefaultValue(null);
        
        $body = sprintf($this->codeLibrary()->get("form.constructor.parentCall"), $dataSet->getName());
        
        foreach ($dataSet->listGenerator() as $column)
        {
            $name = $column->getName();
            $columnInfo = $column->info();
            if (isset($columnInfo["label"]))
            {
                $body .= sprintf($this->codeLibrary()->get("form.constructor.field.fromWeb"), $columnInfo["name"], $columnInfo["type"], $columnInfo["label"]);
                continue;
            }
            
            if($column->info("key") == "PRI") {
                $body .= sprintf($this->codeLibrary()->get("form.constructor.field.primary"), $name);
                continue;
            }
            $label = ucfirst(preg_replace("/[^a-z0-9]/i", " ", $name));
            $body .= sprintf($this->codeLibrary()->get("form.constructor.field." . $this->getFieldType($column)), $name, $label);
        }
        
        $body .= $this->codeLibrary()->get("form.constructor.field.submit");
        
        $method->setBody($body);
        $class->addMethodFromGenerator($method);
    }
    
     /**
      * generates code for base grid and saves in file (overrides file if exists)
      * 
      * @param DataSetDescriptorInterface $dataSet 
      * @return string
      */
    protected function generateBaseGrid(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Grid";
        $namespace = $this->params->getParam("moduleName") . "\Grid\BaseGrid";
        $fullClassName = '\\' . $namespace . '\\' . $className;
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($this->baseGridParent);
        
        foreach ($this->baseGridUses as $use) {
            $class->addUse($use);
        }
        
        $this->generateConfigProperty($class);
        $this->generateHeaderProperty($class, $dataSet);
        
        $this->generateInitMehod($class, $dataSet);
        $this->generateInitFiltersMehod($class, $dataSet);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('filter.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
                ->setDocBlock($docBlock);
        
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Grid/BaseGrid/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
        return $fullClassName;
    }
    
    /**
     * generates init method
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function generateInitMehod(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $controller = $this->params->getParam("moduleName") . "/" . $dataSet->getName();
        $editLink = $controller . "/edit/%s";
        $deleteLink = $controller . "/delete/%s";;
        $body = sprintf($this->codeLibrary()->get('grid.init.body'), $editLink, $deleteLink);
        
        $method = new MethodGenerator("init");
        $method->setBody($body);
        $method->setFlags(\Zend\Code\Generator\MethodGenerator::FLAG_PUBLIC);
        
        $class->addMethodFromGenerator($method);
    }
    
    /**
     * generates config property
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function generateInitFiltersMehod(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $body = "";
        foreach ($dataSet->listGenerator() as $column) {
            $name = $column->getName();
            $type = $this->getFieldType($column);
            $body .= sprintf($this->codeLibrary()->get('grid.initFilters.' . $type), $name, $name);
        }
        
        $method = new MethodGenerator("initFilters");
        $method->setBody($body);
        $method->setFlags(\Zend\Code\Generator\MethodGenerator::FLAG_PROTECTED);
        
        $parameter = new \Zend\Code\Generator\ParameterGenerator("query");
        $parameter->setType("\Zend\Db\Sql\Select");
        
        $method->setParameter($parameter);
        $class->addMethodFromGenerator($method);
    }

    /**
     * generates config property
     * @param \Zend\Code\Generator\ClassGenerator $class
     */
    protected function generateConfigProperty(ClassGenerator $class)
    {
        $property = new \Zend\Code\Generator\PropertyGenerator("config");
        $property->setFlags(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED);
        $property->setDefaultValue(array(
            'name' => 'List',
            'showPagination' => true,
            'showQuickSearch' => false,
            'showItemPerPage' => true,
            'itemCountPerPage' => 10,
            'showColumnFilters' => true,
        ));
        
        $class->addPropertyFromGenerator($property);
    }
    
    /**
     * generates headers
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function generateHeaderProperty(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $property = new \Zend\Code\Generator\PropertyGenerator("headers");
        $property->setFlags(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED);
        $value = array();
        
        foreach ($dataSet->listGenerator() as $field)
        {
            $value[$field->getName()] = array(
                'title' => ucfirst(preg_replace("/[^a-z0-9]/i", " ", $field->getName())),
                'width' => '100',
                'filters' => ($this->getFieldType($field) == 'bool') ? array(null => 'All' , 1 => 'True' , 0 => 'False') : "text",
            );
        }
        
        $property->setDefaultValue($value);
        
        $class->addPropertyFromGenerator($property);
    }
    
    /**
     * generates file with target grid (if not exists yet)
     * 
     * @param DataSetDescriptorInterface $dataSet
     * @param string $extends
     */
    protected function generateGrid(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Grid";
        $namespace = $this->params->getParam("moduleName") . "\Grid";
        $fullClassName = '\\' . $namespace . '\\' . $className;
        
        $gridClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Grid/" . $className . ".php";
        if (file_exists($gridClassFilePath)) {
            return $fullClassName;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($extends);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('grid.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($gridClassFilePath, $file->generate());
        return $fullClassName;
    }
    
    /**
     * gets field type for PHP
     * @param FieldDescriptorInterface $column
     * @return string
     */
    protected function getFieldType(FieldDescriptorInterface $column)
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