<?php
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Generator\Params;
use VisioCrudModeler\Generator\Config\Config;
use VisioCrudModeler\Generator\Strategy\WebGenerator;

/**
 * controller for handling console commands
 *
 * @author bweres01
 *        
 */
class WebController extends AbstractActionController
{

    /**
     * handles running generators
     */
    public function generateAction()
    {
        
        $json = '{"params":{"author":"VisioCrudModeler","copyright":"HyPHPers","project":"VisioCrudModeler generated models","license":"MIT","moduleName":"Crud","descriptor":"web"},"tables":[{"name":"customer","class":"Customer"},{"name":"product","class":"Product"}],"elements":[{"name":"idcustomer","table":"customer","label":"Idcustomer","type":"text","validators":[{"type":"digits"}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"name","table":"customer","label":"Name","type":"text","validators":[{"type":"required"},{"type":"string-length","options":{"min":"0","max":"100"}}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"surname","table":"customer","label":"Surname","type":"text","validators":[{"type":"required"},{"type":"string-length","options":{"min":"0","max":"100"}}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"city","table":"customer","label":"City","type":"text","validators":[{"type":"required"},{"type":"string-length","options":{"min":"0","max":"100"}}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"street","table":"customer","label":"Street","type":"text","validators":[{"type":"required"},{"type":"string-length","options":{"min":"0","max":"100"}}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"active","table":"customer","label":"Active","type":"text","validators":[{"type":"digits"}],"filters":[]},{"name":"edit1","table":"customer","label":"Edit1","type":"text","validators":[{"type":"digits"}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"id","table":"product","label":"Id","type":"text","validators":[{"type":"digits"}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"customer_id","table":"product","label":"CustomerId","type":"text","validators":[{"type":"required"},{"type":"digits"}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]},{"name":"product","table":"product","label":"Product","type":"text","validators":[{"type":"required"},{"type":"string-length","options":{"min":"0","max":"100"}}],"filters":[{"type":"string-trim"},{"type":"strip-tags"}]}]}';
        $postParams = json_decode($json, true);
        
        $mergeDefaultParamsWithModeleted = array_merge($this->getServiceLocator()->get('config')['VisioCrudModeler']['params'] , $postParams['params']);
        $params = Params::factory($this->getRequest(), $mergeDefaultParamsWithModeleted);
        $params->setParam('config', new Config($this->getServiceLocator()->get('config')['VisioCrudModeler']));
        $params->setParam('modeler' , $postParams);
        
        $generatorStrategy = new WebGenerator($params);
        $generatorStrategy->setServiceLocator($this->getServiceLocator());
        $generatorStrategy->generate();
    }
    
}