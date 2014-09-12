<?php
namespace VisioCrudModeler\Generator;


use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;
/**
 * generator class for creating Controller classes
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>, robertbodych
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
class ControllerGenerator implements GeneratorInterface
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
        
        $controllerArray = array();
        foreach ($descriptor->listGenerator() as $name=>$dataSet) {
        	$result = $this->generateController($name, $dataSet);
        	$this->generateBaseController($name);
        	$controllerName = $this->underscoreToCamelCase->filter($name);
        	
        	
        	$moduleName = $this->params->getParam("moduleName");
        	
        	$key = $moduleName.'\Controller\\'.$controllerName;
        	
        	$controllerArray[$key] = $key.'Controller';
        		
        	
        	$this->console(sprintf('writing generated controllers for "%s" table', $name));
        }
        
        return array('controllers' => array(
        
        		'invokables' => $controllerArray
        ));
        
       
    }
    

    /*
     * (non-PHPdoc)
    * @see \VisioCrudModeler\Generator\GeneratorInterface::generateController()
    */
    protected function generateController($name)
    {
    	$className = $this->underscoreToCamelCase->filter($name).'Controller';
    	$moduleName = $this->params->getParam("moduleName");
    	$file = new FileGenerator();
    	$file->setFilename($className);
    	$file->setNamespace($moduleName)
    	->setUse('Zend\Mvc\Controller\AbstractActionController');
    		
    	$class      = new ClassGenerator();
    	$docblock = DocBlockGenerator::fromArray(array(
    			'shortDescription' => 'Sample generated class',
    			'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
    			'tags'             => array(
    					array(
    							'name'        => 'version',
    							'description' => '$Rev:$',
    					),
    					array(
    							'name'        => 'license',
    							'description' => 'New BSD',
    					),
    			),
    	));
    	$class->setName($className);
    	
    	$class->setExtendedClass('Base'.$className);
    	$class->setDocblock($docblock);
    	
    	$file->setClass($class);
    	    	
    	$modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Controller/" . $className . ".php";
    	file_put_contents($modelClassFilePath, $file->generate());
    	
    }
    
    
    protected function generateBaseController($name)
    {
    	

    	$className = 'Base'.$this->underscoreToCamelCase->filter($name).'Controller';
    	 
    	$moduleName = $this->params->getParam("moduleName");
    	
    	$fileBase = new FileGenerator();
    	$fileBase->setFilename($className);
    	$fileBase->setNamespace($moduleName)
    	->setUse('Zend\Mvc\Controller\AbstractActionController');
    	 
    	$classBase      = new ClassGenerator();
    	$docblockBase = DocBlockGenerator::fromArray(array(
    			'shortDescription' => 'Sample generated class',
    			'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
    			'tags'             => array(
    					array(
    							'name'        => 'version',
    							'description' => '$Rev:$',
    					),
    					array(
    							'name'        => 'license',
    							'description' => 'New BSD',
    					),
    			),
    	));
    	$classBase->setName($className);
    	
    	$index = new MethodGenerator();
    	$index->setName('indexAction');
    	
    	$create = new MethodGenerator();
    	$create->setName('createAction');
    	
    	$read = new MethodGenerator();
    	$read->setName('readAction');
    	
    	$update = new MethodGenerator();
    	$update->setName('updateAction');
    	
    	$delete = new MethodGenerator();
    	$delete->setName('deleteAction');
    	
    	
    	$classBase->setExtendedClass('AbstractActionController');
    	//$fooBase->set
    	$classBase->setDocblock($docblockBase);
    	
    	$classBase->addMethodFromGenerator($index);
    	
    	$classBase->addMethodFromGenerator($create);
    	$classBase->addMethodFromGenerator($read);
    	$classBase->addMethodFromGenerator($update);
    	$classBase->addMethodFromGenerator($delete);
    	
    	$fileBase->setClass($classBase);
    	 
    	
    	
    	$modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Controller/" . $className . ".php";
    	file_put_contents($modelClassFilePath, $fileBase->generate());
    	 
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
    
    
    public function __construct()
    {
    	$this->underscoreToCamelCase = new UnderscoreToCamelCase();
    }
}