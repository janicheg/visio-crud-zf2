<?php

namespace VisioCrudModeler\JQueryValidator;


interface ValidatorManagerAwareInterface
{
    
    
     /**
     * Get validator  manager
     *
     * @param \VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager
     */
    public function getValidatorManager();
    
    
    /**
     * Set validator  manager
     *
     * @param \VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager
     */
    public function setValidatorManager(\VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager);
    
}
