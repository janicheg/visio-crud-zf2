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

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        // Debug::dump($db);
        // $query=$db->query('SELECT * FROM information_schema.TABLES');
        
        $stmt = $db->createStatement('SELECT * FROM information_schema.TABLES it WHERE it.TABLE_SCHEMA = :database')->execute([
            'database' => 'K08_www_biedronka_pl'
        ]);
        //Debug::dump($stmt);
        return new ViewModel(array(
            'stmt' => $stmt
        ));
    }
}
