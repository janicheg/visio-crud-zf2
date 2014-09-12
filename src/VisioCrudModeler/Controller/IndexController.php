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
use VisioCrudModeler\Filter\CustomerFilter;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $dataSourceDescriptor=new DbDataSourceDescriptor($db);
        
        $dependencyTree=$this->getServiceLocator()->get('config')['VisioCrudModeler']['dependency'];
        \Zend\Debug\Debug::dump($dependencyTree);
        $dependency=new Dependency($dependencyTree);
        \Zend\Debug\Debug::dump(var_export($dependency->dependencyListFor('all'),true));
        
        return new ViewModel(array(
        	'descriptor'=>$dataSourceDescriptor
        ));
    }
    
    
    public function modelerAction()
    {
        $adapter = $this->getDbAdapter();
        
        $dataSourceDescriptor=new DbDataSourceDescriptor($adapter);
        
        $form = new \VisioCrudModeler\Form\CustomerForm();
        $customerFilter = new CustomerFilter();
        $form->setInputFilter($customerFilter->getInputFilter());
        
        return array(
            'tables' => $dataSourceDescriptor->listDataSets(),
            'form' => $form , 
            'dataSourceDescriptor'=> $dataSourceDescriptor,
            'underscoreToCamelCase' => new \Zend\Filter\Word\UnderscoreToCamelCase(),
            'config' =>  $this->getServiceLocator()->get('config')['VisioCrudModeler']['params']
        );
    }
    
    
    /**
     * 
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }
    
    
}
