<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;

/**
 * Description of Required
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class Max  extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        $ret = '';
        
        $validator = $this->getZendValidator();
        if($validator->getMax()){
            $ret .= 'max: ' . $validator->getMax() . ', ' . PHP_EOL;
        }
        
        return $ret;
    }    
}
