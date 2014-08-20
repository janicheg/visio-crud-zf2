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
use Zend\Debug\Debug;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\Sql\Select;
use Zend\Di\Di;
use VisioCrudModeler\DataSource\DbDataSource;
use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        // Debug::dump($db);
        // $query=$db->query('SELECT * FROM information_schema.TABLES');
        
        $dataSourceDescriptor=new DbDataSourceDescriptor($db, 'K08_www_biedronka_pl');
        
        
        //Debug::dump($stmt);
        return new ViewModel(array(
        	'descriptor'=>$dataSourceDescriptor
        ));
    }
}
