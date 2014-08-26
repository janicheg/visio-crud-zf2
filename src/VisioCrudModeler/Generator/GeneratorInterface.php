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
     * @param \VisioCrudModeler\Generator\ParamsInterface $params            
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params);
}