<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace VisioCrudModeler\Model\TableGateway\Entity;

use Zend\Db\Sql\Sql;

class CustomerTable extends \VisioCrudModeler\Model\TableGateway\AbstractTable {

    
    protected $table = 'customer';

    
    protected $arrayObjectPrototypeClass = '\VisioCrudModeler\Model\TableGateway\Entity\Customer';
    
    
}
