<?php

namespace VisioCrudModeler\Filter;

use VisioCrudModeler\Filter\BaseFilter\BaseCustomerFilter;

/**
 * Description of Examle
 *
 * @author  Piotr Duda (dudapiotrek@gmail.com)
 */
class CustomerFilter extends BaseCustomerFilter
{
    
    public function __construct()
    {
        parent::__construct();
        
        
        $inputFilter = $this->getInputFilter();
        $factory = $this->getInputFactory();
        
        $inputFilter->replace($factory->createInput(array(
                    'name' => 'surname_test',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 2,
                                'max' => 100,
                            ),
                        ),
                    ),
        )), 'surname_test');
        
        
        
    }
    
}
