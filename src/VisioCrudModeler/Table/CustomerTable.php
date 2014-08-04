<?php

namespace VisioCrudModeler\Table;

use ZfTable\AbstractTable;

class CustomerTable extends AbstractTable
{
    
    protected $config = array(
        'name' => 'List',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => true,
    );
    
    
    //Definition of headers
    protected $headers = array(
        'id' => array('title' => 'Id', 'width' => '50') ,
        'name' => array('title' => 'Name' , 'filters' => 'text'),
        'surname_test' => array('title' => 'Surname' , 'filters' => 'text' ),
        'street' => array('title' => 'Street' , 'filters' => 'text'),
        'city' => array('title' => 'City'),
        'active' => array('title' => 'Active' , 'width' => 100 , 'filters' => array( null => 'All' , 1 => 'Active' , 0 => 'Inactive')),
        'edit' => array('title' => 'Edit', 'width' => '100') ,
        'delete' => array('title' => 'Delete', 'width' => '100') ,
    );

    public function init()
    {
        $this->getHeader('edit')->getCell()->addDecorator('callable', array(
            'callable' => function($context, $record){
                return sprintf('<a href="/visio-crud-modeler/customer/edit/%s">Edit</a>', $record['id']);
            }
        ));
        
        $this->getHeader('delete')->getCell()->addDecorator('callable', array(
            'callable' => function($context, $record){
                return sprintf('<a href="/visio-crud-modeler/customer/delete/%s">Delete</a>', $record['id']);
            }
        ));
    }

    protected function initFilters(\Zend\Db\Sql\Select $query)
    {
        if ($value = $this->getParamAdapter()->getValueOfFilter('name')) {
            $query->where("name like '%".$value."%' ");
        }
        if ($value = $this->getParamAdapter()->getValueOfFilter('surname_test')) {
            $query->where("surname_test like '%".$value."%' ");
        }
        if ($value = $this->getParamAdapter()->getValueOfFilter('street')) {
            $query->where("street like '%".$value."%' ");
        }
        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active = '".$value."' ");
            
        }
    }

}