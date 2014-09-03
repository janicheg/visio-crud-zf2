<?php
namespace VisioCrudModeler\Generator;

/**
 * generator class for creating Model classes
 *
 * @author bweres01
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
        }
        
    }
    
    /**
     * generates code for base model and saves in file.
     * @param string $name
     * @param \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet
     */
    protected function generateBaseModel($name, \VisioCrudModeler\Descriptor\ListGeneratorInterface $dataSet)
    {
        $this->console($name);
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
}