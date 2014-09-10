<?php
namespace VisioCrudModeler\Generator\Strategy;

/**
 * strategy class for running generators according to passed params
 *
 * @author bweres01
 *        
 */
class ExecuteGenerator extends AbstractGenerator
{

    /**
     * runs specified generators
     */
    public function generate()
    {
        // setting up params
        $this->printParams();
        $this->params->setParam('di', $this->getDi());
        $this->params->setParam('descriptor', $this->getDescriptor());
        $runtimeConfiguration = $this->readRuntimeConfig();
        $this->params->setParam('runtimeConfiguration', $runtimeConfiguration);
        
        
        $dependency = $this->dependency();
        foreach ($dependency->dependencyListFor($this->params->getParam('generator')) as $name) {
            
            $this->console("\n" . 'Running generator: ' . $name);
            
            $result = $this->getGenerator($name)->generate($this->params);
            $runtimeConfiguration->set($name, (array) $result);
        }
        $this->writeRuntimeConfig($runtimeConfiguration);
    }
    
    
}