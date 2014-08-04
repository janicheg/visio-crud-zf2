<?php

namespace VisioCrudModeler\Filter;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface; 

/**
 * AbstractFilter definition
 *
 * @author  Piotr Duda (dudapiotrek@gmail.com)
 */
class AbstractFilter
{
 
    /**
     *
     * @var \Zend\InputFilter\Factory 
     */
    protected $inputFactory;
    
    /**
     *
     * @var \Zend\InputFilter\InputFilterInterface
     */
    protected $inputFilter;
    
    /**
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter= $inputFilter;
    }
 
    /**
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if(!$this->inputFilter){
            $this->inputFilter = new InputFilter();
        }
        return $this->inputFilter;
    } 
    
    /**
     * 
     * @return \Zend\InputFilter\Factory
     */
    public function getInputFactory()
    {
        if(!$this->inputFactory){
            $this->inputFactory = new InputFactory();
        }
        return $this->inputFactory;
    }

    /**
     * 
     * @param \Zend\InputFilter\Factory $inputFactory
     */
    public function setInputFactory(\Zend\InputFilter\Factory $inputFactory)
    {
        $this->inputFactory = $inputFactory;
    }


    
}
