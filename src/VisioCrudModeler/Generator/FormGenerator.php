<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;

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
        
        foreach ($descriptor->listGenerator() as $name=>$dataSet) {
            $this->generateBaseGrid($name, $dataSet);
            $this->generateGrid($name);
            
            $this->generateBaseForm($name, $dataSet);
            $this->generateForm($name);
            
            $this->console(sprintf('writing generated forms for "%s" table', $name));
        }
    }
    
    protected function generateForm($name)
    {
        $className = $this->underscoreToCamelCase->filter($name) . "Form";
        $gridClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Form/" . $className . ".php";
        if (file_exists($gridClassFilePath)) {
            return;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $namespace = $this->params->getParam("moduleName") . "\Form";
        $class->setNamespaceName($namespace);
        $class->setExtendedClass("\\" . $namespace . "\BaseForm\Base" . $className);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('form.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($gridClassFilePath, $file->generate());
    }
    
     /**
     * generates code for forms and saves in file (overrides file if exists)
     * @param string $name
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateBaseForm($name, $dataSet)
    {
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Form";
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($this->params->getParam("moduleName") . "\Form\BaseForm");
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
        
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Form/BaseForm/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
    }
    
    /**
     * generates init method
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateFormConstruct(ClassGenerator $class, \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet)
    {
        $method = new MethodGenerator("__contruct");
        $param = new \Zend\Code\Generator\ParameterGenerator("name");
        $param->setDefaultValue(null);
        
        $body = sprintf($this->codeLibrary()->get("form.constructor.parentCall"), $dataSet->getName());
        
        foreach ($dataSet->listGenerator() as $column)
        {
            $name = $column->getName();
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
     * @param string $name
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateBaseGrid($name, $dataSet)
    {
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Grid";
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($this->params->getParam("moduleName") . "\Grid\BaseGrid");
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
    }
    
    /**
     * generates init method
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateInitMehod(ClassGenerator $class, $dataSet)
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
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateInitFiltersMehod(ClassGenerator $class, \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet)
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
     * @param \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor $dataSet
     */
    protected function generateHeaderProperty(ClassGenerator $class, \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor $dataSet)
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
     * @param string $name
     */
    protected function generateGrid($name)
    {
        $className = $this->underscoreToCamelCase->filter($name) . "Grid";
        $gridClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Grid/" . $className . ".php";
        if (file_exists($gridClassFilePath)) {
            return;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $namespace = $this->params->getParam("moduleName") . "\Grid";
        $class->setNamespaceName($namespace);
        $class->setExtendedClass("\\" . $namespace . "\BaseGrid\Base" . $className);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('grid.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($gridClassFilePath, $file->generate());
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