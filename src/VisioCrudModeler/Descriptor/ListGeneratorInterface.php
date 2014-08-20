<?php
namespace VisioCrudModeler\Descriptor;

/**
 * interface for object implementing generator
 *
 * object implementing this interface will yield its child objects as value
 * while key will hold its coresponding key
 *
 * @author bweres01
 *        
 */
interface ListGeneratorInterface
{

    /**
     * list generator
     *
     * @return Generator
     */
    public function listGenerator();
}