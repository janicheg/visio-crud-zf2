<?php
namespace VisioCrudModeler\Descriptor;

/**
 * referencing field interface
 *
 * for field descriptor capable of holding reference to another field this interface will allow to use 
 * that reference to resolve referenced field location in DataSource structure
 *
 * @author bweres01
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
interface ReferenceFieldInterface
{

    /**
     * returns referenced DataSet name
     *
     * @return string
     */
    public function referencedDataSetName();

    /**
     * returns referenced Field name
     *
     * @return string
     */
    public function referencedFieldName();
}