<?php
namespace VisioCrudModeler\Generator\Request;

use VisioCrudModeler\Generator\Params;
use Zend\Http\Request;

/**
 * Http request generator params class
 *
 * @author bweres01
 *        
 */
class Http extends Params
{

    /**
     * holds request object
     *
     * @var Request
     */
    protected $request = null;

    /**
     * constructor, accepts default params and also those of http request
     *
     * @param array $params            
     * @param Request $request            
     */
    public function __construct(array $params, Request $request)
    {
        $this->request = $request;
        $this->storage = new \ArrayObject(array_merge($params, $request->getParams()->toArray()));
    }

    /**
     * returns http request object
     *
     * @return \Zend\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}