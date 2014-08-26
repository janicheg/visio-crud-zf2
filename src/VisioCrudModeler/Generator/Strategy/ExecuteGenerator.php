<?php
namespace VisioCrudModeler\Generator\Strategy;

use VisioCrudModeler\Generator\ParamsInterface;

/**
 * strategy class for running generators according to passed params
 *
 * @author bweres01
 *        
 */
class ExecuteGenerator
{

    /**
     * holds passed params object
     *
     * @var ParamsInterface
     */
    protected $params = null;

    /**
     * constructor, takes generator params interface
     *
     * @param ParamsInterface $params            
     */
    public function __construct(ParamsInterface $params)
    {
        $this->params = $params;
    }
}