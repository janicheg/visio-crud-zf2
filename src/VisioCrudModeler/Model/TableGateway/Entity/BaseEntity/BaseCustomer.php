<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace VisioCrudModeler\Model\TableGateway\Entity\BaseEntity;

use Zend\EventManager\EventManagerInterface;

class BaseCustomer extends \VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity
{
    
    /**
     *
     * @var int
     */
    public $id;
    
    /**
     *
     * @var string
     */
    public $name;
    
    
    /**
     *
     * @var string
     */
    public $surnameTest;
    
    /**
     *
     * @var string
     */
    public $city;
    
    /**
     *
     * @var string
     */
    public $street;
    
    
    /**
     *
     * @var int
     */
    public $active;
    
    
    public function preInsert(EventManagerInterface $eventManager)
    {
        echo('pre');
    }
    
    public function postInsert(EventManagerInterface $eventManager)
    {
        echo('post');
    }
    
    public function preUpdate(EventManagerInterface $eventManager)
    {
        echo('pre update');
    }
    
    public function postUpdate(EventManagerInterface $eventManager)
    {
        echo('post update');
    }
    
    public function preDelete(EventManagerInterface $eventManager)
    {
        echo('pre delete');
    }
    
    public function postDelete(EventManagerInterface $eventManager)
    {
        echo('post delete');
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
    
    public function getSurnameTest()
    {
        return $this->surnameTest;
    }

    public function setSurnameTest($surnameTest)
    {
        $this->surnameTest = $surnameTest;
    }




    
}
