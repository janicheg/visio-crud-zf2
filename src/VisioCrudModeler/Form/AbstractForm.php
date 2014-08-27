<?php

namespace VisioCrudModeler\Form;

use Zend\Form\Form;
use VisioCrudModeler\JQueryValidator\ValidatorManager;
use VisioCrudModeler\JQueryValidator\ValidatorManagerAwareInterface; 


/**
 * Description of AbstractForm
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
abstract class AbstractForm extends Form implements ValidatorManagerAwareInterface
{
    
    /**
     *
     * @var ValidatorManager 
     */
    protected $validatorManager;
    
    
    /**
     * Get validator  manager
     *
     * @param ValidatorManager $validatorManager
     */
    public function getValidatorManager()
    {
        
        if(!$this->validatorManager){
            $this->validatorManager = new \VisioCrudModeler\JQueryValidator\ValidatorManager();
            $this->validatorManager->setInputFilter($this->getInputFilter());
        }
        return $this->validatorManager;
    }

    /**
     * Set validator  manager
     *
     * @param ValidatorManager $validatorManager
     */
    public function setValidatorManager(\VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager)
    {
        $this->validatorManager = $validatorManager;
    }
    
    /**
     * Return prepeared jquery validate plugin script
     * 
     * @return string
     */
    public function getJqueryValidateScript()
    {
        return $this->getValidatorManager()->getScript('customer');
    }
    
}
