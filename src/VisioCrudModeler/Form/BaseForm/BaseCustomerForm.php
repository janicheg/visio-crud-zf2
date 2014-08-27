<?php

/**
 * Description of CustomerForm
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */

namespace VisioCrudModeler\Form\BaseForm;

use VisioCrudModeler\Form\AbstractForm;


class BaseCustomerForm extends AbstractForm
{
    public function __construct($name = null)
    {
        parent::__construct('customer');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => "name",
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'surname_test',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Surname Test',
            ),
        ));
        
        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));
        
        $this->add(array(
            'name' => 'street',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Street',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'active',
            'attributes' => array(
                'type'  => 'checkbox',
                'class' => ''
            ),
            'options' => array(
                'label' => 'Active',
            ),
        ));
      
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'form-control btn-success',
                'style' => 'width: 50%'
            ),
        ));
    }
    
    
}