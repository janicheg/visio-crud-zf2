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
    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        if ($params->descriptor instanceof \VisioCrudModeler\Descriptor\ListGeneratorInterface) {
            \Zend\Debug\Debug::dump($params->descriptor);
            return;
        }
        
        foreach ($this->descriptor->listGenerator() as $name=>$dataSet) {
            echo $name;
            if ($dataSet instanceof \VisioCrudModeler\Descriptor\ListGeneratorInterface) {
                continue;
            }
            foreach ($dataSet->listGenerator() as $field=>$description) {
                
            }
        }
        
    }
}