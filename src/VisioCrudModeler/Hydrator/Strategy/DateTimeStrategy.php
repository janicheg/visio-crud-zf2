<?php

/**
 * Description of DateTimeStrategy
 *
 * @author Piotr Duda (dudapiotrek@gmail.com)
 */
namespace VisioCrudModeler\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

/**
 * Standard DateTime strategy
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class DateTimeStrategy extends DefaultStrategy
{
  /**
   *
   * Convert a string value into a DateTime object
   */
  public function hydrate($value)
  {
      if (is_string($value)) {
          $value = new DateTime($value);
      }
 
      return $value;
  }
  
  /**
   * Extract DateTime object to string
   * 
   * @param type $value
   * @return type
   */
  public function extract($value)
  {
      return $value->format('Y-m-d H:i:s');
  }
  
}