<?php

namespace VisioCrudModeler\JQueryValidator;


interface ValidatorInterface
{
    /**
     * 
     * Get rule string for Jquery Validate
     * 
     * @return string
     */
    public function getRule();
}
