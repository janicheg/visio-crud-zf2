<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * generator class for creating InputFilter classes
 *
 * @author Jacek Pawelec jacek.pawelec@isobar.com
 *        
 */
class InputFilterGenerator implements GeneratorInterface
{
    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;
    
    protected $baseFilterParent = "\VisioCrudModeler\Filter\AbstractFilter";
    
    protected $baseFilterUses = array();
    
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
            $this->generateBaseFilter($name, $dataSet);
            $this->generateFilter($name);
            
            $this->console(sprintf('writing generated filter for "%s" table', $name));
        }
        
    }
    
    protected function generateFilter($name)
    {
        $className = $this->underscoreToCamelCase->filter($name) . "Filter";
        $filterClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Filter/" . $className . ".php";
        if (file_exists($filterClassFilePath)) {
            return;
        }
        
        $class = new ClassGenerator();
        $class->setName($className);
        $namespace = $this->params->getParam("moduleName") . "\Filter";
        $class->setNamespaceName($namespace);
        $class->setExtendedClass("\\" . $namespace . "\BaseFilter\Base" . $className);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('filter.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
            ->setDocBlock($docBlock);
        
        file_put_contents($filterClassFilePath, $file->generate());
    }
    
    /**
     * generates code for base filter and saves in file (overrides file if exists)
     * @param string $name
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateBaseFilter($name, $dataSet)
    {
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Filter";
        
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($this->params->getParam("moduleName") . "\Filter\BaseFilter");
        $class->setExtendedClass($this->baseFilterParent);
        
        foreach ($this->baseFilterUses as $use) {
            $class->addUse($use);
        }
        
        $this->generateConstructor($class, $dataSet);
        
        $file = new FileGenerator();
        
        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('filter.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
            
        $file->setClass($class)
                ->setDocBlock($docBlock);
        
        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Filter/BaseFilter/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
    }
    
    /**
     * generates propertiy, getter, setter and other methods...
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor $dataSet
     */
    protected function generateConstructor(ClassGenerator $class, \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor $dataSet)
    {
        $constructor = new MethodGenerator("__construct");
        $constructor->addFlag(MethodGenerator::FLAG_PUBLIC);
        
        $methodBody = $this->codeLibrary()->get("filter.constructor.body.begin");
        
        foreach ($dataSet->listGenerator() as $column) {
            $methodBody .= $this->generateFilterForColumn($column);
        }
        
        $constructor->setBody($methodBody);
        
        $class->addMethodFromGenerator($constructor);
    }
    
    /**
     * generates filters and validators for column
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     * @return string code for columd data
     */
    protected function generateFilterForColumn(\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column)
    {
        $fieldFilter = $this->codeLibrary()->get("filter.constructor.body.input");
        
        $name = $column->getName();
        $required = $column->info()['null'] ? 'false' : 'true';
        
        $type = $this->getFieldType($column);
        $filters = $this->codeLibrary()->get('filter.constructor.fieldFilter' . ucfirst($type));
        
        $validators = $this->generateValidators($column);
        
        return sprintf($fieldFilter, $name, $required, $filters, $validators);
    }
    
    /**
     * generates validators array code
     * @param \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column
     * @return string Code for validators
     */
    protected function generateValidators(\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor $column)
    {
        $columnInfo = $column->info();
        
        $validators = "";
        
        switch ($this->getFieldType($column)) {
            case "int":
                $validators .= $this->codeLibrary()->get("filter.constructor.validators.digits");
                break;
            case "float":
                break;
            case "string":
                $validator = $this->codeLibrary()->get("filter.constructor.validators.stringLenght");
                $validator = sprintf($validator, ($columnInfo["null"] ? 0 : 1), $columnInfo["character_maximum_length"]);
                $validators .= $validator;
                break;
        }
        
        return sprintf($this->codeLibrary()->get("filter.constructor.validators"), $validators);
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