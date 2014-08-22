<?php
namespace VisioCrudModeler\Generator;

/**
 * representation of generator params
 *
 * @author bweres01
 *        
 */
interface ParamsInterface
{

    /**
     * gets single param by name
     *
     * @param string $name            
     * @return mixed
     */
    public function getParam($name);

    /**
     * gets array/object holding params
     *
     * @return mixed
     */
    public function getParams();

    /**
     * sets param value by name
     *
     * @param string $name            
     * @param mixed $value            
     * @return void
     */
    public function setParam($name, $value);

    /**
     * sets params array/object
     *
     * @param mixed $params            
     * @return void
     */
    public function setParams($params);
}