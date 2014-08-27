<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;

/**
 * Description of Required
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class Digits  extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        return 'digits:  true, ' . PHP_EOL;
    }    
}
