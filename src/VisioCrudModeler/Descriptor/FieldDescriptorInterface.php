<?php
namespace VisioCrudModeler\Descriptor;

/**
 * field descriptor interface
 *
 * @author bweres01
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
interface FieldDescriptorInterface
{

    /**
     * returns name of the field
     *
     * @return string
     */
    public function getName();

    /**
     * returns type of the field
     *
     * @return string
     */
    public function getType();
}