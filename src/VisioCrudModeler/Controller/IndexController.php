<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;
use VisioCrudModeler\Generator\Dependency;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $dataSourceDescriptor=new DbDataSourceDescriptor($db, 'K08_www_biedronka_pl');
        
        $dependencyTree=$this->getServiceLocator()->get('config')['VisioCrudModeler']['dependency'];
        \Zend\Debug\Debug::dump($dependencyTree);
        $dependency=new Dependency($dependencyTree);
        \Zend\Debug\Debug::dump($dependency->dependencyListFor('model'));
        
        return new ViewModel(array(
        	'descriptor'=>$dataSourceDescriptor
        ));
    }
}