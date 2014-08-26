<?php
namespace VisioCrudModeler\Generator\Request;

use VisioCrudModeler\Generator\Params;
use Zend\Console\Request;

/**
 * Console request generator params
 *
 * @author bweres01
 *        
 */
class Console extends Params
{

    /**
     * holds console request
     *
     * @var Request
     */
    protected $request = null;

    /**
     * constructor, accepts default params and one from request
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
     * returns request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}