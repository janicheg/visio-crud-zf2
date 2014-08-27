<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;

/**
 * Description of Required
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class Min  extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        $ret = '';
        
        $validator = $this->getZendValidator();
        if($validator->getMin()){
            $ret .= 'min: ' . $validator->getMin() . ', ' . PHP_EOL;
        }
        
        return $ret;
    }    
}
