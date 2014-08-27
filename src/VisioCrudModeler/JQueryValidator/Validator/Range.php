<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;

/**
 * Description of Required
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class Range  extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        $ret = '';
        
        $validator = $this->getZendValidator();
        if($validator->getMin() && $validator->getMax()){
            $ret .= 'range: [' . $validator->getMin() . ' , ' . $validator->getMax() .' ]' . PHP_EOL;
        }
        
        return $ret;
    }    
}
