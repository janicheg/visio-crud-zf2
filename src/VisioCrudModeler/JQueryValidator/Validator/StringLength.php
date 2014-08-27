<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;


/**
 * Description of StringLength
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class StringLength extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        $ret = '';
        $validator = $this->getZendValidator();
        
        if($validator->getMin()){
            $ret .= 'minlength: ' . $validator->getMin() . ', ' . PHP_EOL;
        }
        if($validator->getMax()){
            $ret .= 'maxlength: ' . $validator->getMax() . ', ' . PHP_EOL;
        }
        
        return $ret;
        
    }    
}
