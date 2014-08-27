<?php

namespace VisioCrudModeler\JQueryValidator;

/**
 * Description of ValidatorFactory
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class ValidatorFactory
{
        
    /**
     * Mapper between Zend Validate Class and Jquery ekvivalent
     * 
     * @var array
     */
    public static $mapper = array(
        'Required' => 'VisioCrudModeler\JQueryValidator\Validator\Required',
        'Zend\Validator\StringLength' => 'VisioCrudModeler\JQueryValidator\Validator\StringLength',
        'Zend\Validator\EmailAddress' => 'VisioCrudModeler\JQueryValidator\Validator\Email',
        'Zend\Validator\Digits' => 'VisioCrudModeler\JQueryValidator\Validator\Digits',
        'Zend\Validator\GreaterThan' => 'VisioCrudModeler\JQueryValidator\Validator\Min',
        'Zend\Validator\LessThan' => 'VisioCrudModeler\JQueryValidator\Validator\Max',
        'Zend\Validator\Between' => 'VisioCrudModeler\JQueryValidator\Validator\Range',
        
    );
    
    
    /**
     * 
     * Factory Validator
     * 
     * @param string $name
     * @param mixed $validatorOrValue
     * @return \VisioCrudModeler\JQueryValidator\ValidatorInterface
     * @throws \Exception
     */
    public static function factory($name, $validatorOrValue = false)
    {
        if(array_key_exists($name, self::$mapper)){
            $className = self::$mapper[$name];
            return new $className($validatorOrValue);
        }else{
            throw new \Exception('Given validator doesn not exists: ' . $name);
        }
    }
    
    
}
