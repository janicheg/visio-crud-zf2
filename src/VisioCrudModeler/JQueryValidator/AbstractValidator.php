<?php

namespace VisioCrudModeler\JQueryValidator;

/**
 * Description of AbstractValidator
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
abstract class AbstractValidator implements ValidatorInterface
{
    
    /**
     *
     * @var \Zend\Validator\ValidatorInterface 
     */
    protected $zendValidator = null;
    
    
    /**
     * Value for special validation eg. requierd
     * @var mixed
     */
    protected $value = null;
    
    
    /**
     *  
     * 
     * @param mixed $validator 
     */
    public function __construct($validator = null)
    {
        if($validator instanceof \Zend\Validator\ValidatorInterface){
            $this->setZendValidator($validator);
        }else{
            $this->setValue($validator);
        }
    }
    
    /**
     * Get Zend validator instance
     * @return \Zend\Validator\ValidatorInterface 
     */
    public function getZendValidator()
    {
        return $this->zendValidator;
    }
    
    /**
     * Get value for defined validator (eg true/false for required)
     * @return type
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set Zend validator instance
     * @param \Zend\Validator\ValidatorInterface $zendValidator
     */
    public function setZendValidator(\Zend\Validator\ValidatorInterface $zendValidator)
    {
        $this->zendValidator = $zendValidator;
    }

    /**
     * Set value for defined validator (eg true/false for required)
     * @param mixed
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


    
}
