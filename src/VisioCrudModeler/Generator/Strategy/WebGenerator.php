<?php
namespace VisioCrudModeler\Generator\Strategy;

/**
 * strategy class for running generators according to passed params
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 *        
 */
class WebGenerator extends AbstractGenerator
{
    
    /**
     * runs specified generators
     */
    public function generate()
    {
        $this->printParams();
        $this->params->setParam('di', $this->getDi());
        $this->params->setParam('descriptor', $this->getDescriptor());
        $runtimeConfiguration = $this->readRuntimeConfig();
        $this->params->setParam('runtimeConfiguration', $runtimeConfiguration);
        
        $generators = $this->params->getParam('config')->get('generators');
        
        foreach(array_keys($generators) as $generatorName){
            
            if($generatorName == 'all'){
                continue;
            }
            $result = $this->getGenerator($generatorName)->generate($this->params);
            $runtimeConfiguration->set($generatorName, (array) $result);
        }
        
        $this->writeRuntimeConfig($runtimeConfiguration);
    }
   
}