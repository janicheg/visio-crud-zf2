<?php
namespace VisioCrudModeler\Generator;

use VisioCrudModeler\Exception\CircularDependencyException;

/**
 * class for calculating generators dependency
 *
 * @author bweres01
 *        
 */
class Dependency
{

    /**
     * holds dependency
     *
     * @var Array
     */
    protected $dependency = array();

    /**
     * constructor, accepts dependency tree
     *
     * @param array $dependency            
     */
    public function __construct(array $dependency)
    {
        $this->dependency = $dependency;
    }

    /**
     * returns array list with sorted dependency list for given dependency primary key
     *
     * @param string $name            
     * @return array
     */
    public function dependencyListFor($name)
    {
        $list = array(
            $name
        );
        if (! array_key_exists($name, $this->dependency) || count($this->dependency[$name]) == 0) {
            return $list;
        }
        foreach ($this->dependency[$name] as $dependant) {
            if (isset($this->dependency[$dependant]) && is_array($this->dependency[$dependant]) && in_array($name, $this->dependency[$dependant])) {
                throw new CircularDependencyException("'" . $name . "' is dependant on '" . $dependant . "' which is also dependant on '" . $name . "'");
            }
            $list[] = $dependant;
            if (array_key_exists($dependant, $this->dependency) && is_array($this->dependency[$dependant])) {
                foreach ($this->dependency[$dependant] as $secondDependency) {
                    $dependencyList = $this->dependencyListFor($secondDependency);
                    foreach (array_reverse($dependencyList) as $item) {
                        $list[] = $item;
                    }
                }
            }
        }
        return array_unique(array_reverse(array_values($list), false));
    }
}