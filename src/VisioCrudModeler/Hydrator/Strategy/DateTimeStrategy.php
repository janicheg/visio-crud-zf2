<?php

/**
 * Description of DateTimeStrategy
 *
 * @author Piotr Duda (dudapiotrek@gmail.com)
 */
namespace VisioCrudModeler\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeStrategy extends DefaultStrategy
{
  /**
   * {@inheritdoc}
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
   * 
   * @param type $value
   * @return type
   */
  public function extract($value)
  {
      return $value->format('Y-m-d H:i:s');
  }
  
}