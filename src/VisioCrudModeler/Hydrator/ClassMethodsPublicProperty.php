<?php

namespace VisioCrudModeler\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Description of ClassMethodsPublicProperty
 *
 * @author  Piotr Duda (dudapiotrek@gmail.com)
 */
class ClassMethodsPublicProperty extends ClassMethods
{

    /**
     * Extract values from an object with class methods
     *
     * Extracts the getter/setter of the given $object.
     *
     * @param  object                           $object
     * @return array
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                    '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }

        $filter = null;
        if ($object instanceof FilterProviderInterface) {
            $filter = new FilterComposite(
                    array($object->getFilter()), array(new MethodMatchFilter("getFilter"))
            );
        } else {
            $filter = $this->filterComposite;
        }

        $attributes = array();
        $methods = get_class_methods($object);

        $publicParams = call_user_func('get_object_vars', $object);

        foreach ($methods as $method) {

            if (!$filter->filter(get_class($object) . '::' . $method)) {
                continue;
            }

            $attribute = $method;
            
            if (preg_match('/^get/', $method)) {
                $attribute = substr($method, 3);
                if (!property_exists($object, $attribute)) {
                    $attribute = lcfirst($attribute);
                }
            }

            if (!array_key_exists($attribute, $publicParams)) {
                continue;
            }
            
            $attribute = $this->extractName($attribute, $object);
            
            $attributes[$attribute] = $this->extractValue($attribute, $object->$method(), $object);
        }

        return $attributes;
    }

}
