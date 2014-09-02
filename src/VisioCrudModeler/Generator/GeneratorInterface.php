<?php
namespace VisioCrudModeler\Generator;

/**
 * interface for generator class
 *
 * @author bweres01
 *        
 */
interface GeneratorInterface
{

    /**
     * method starts generator logic
     *
     * return configuration can be used in other generators to link to elements created by implementing generator
     *
     * @param \VisioCrudModeler\Generator\ParamsInterface $params            
     * @return array
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params);
}