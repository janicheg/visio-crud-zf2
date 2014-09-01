<?php
namespace VisioCrudModeler\Generator;

class CodeLibrary
{

    /**
     * holds loaded library
     *
     * @var array
     */
    protected $library = null;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->library = require __DIR__ . '/CodeLibraryAssets/code.php';
    }

    /**
     * returns code library entry
     *
     * @param string $identifier            
     */
    public function get($identifier)
    {
        if (isset($this->library[$identifier])) {
            return $this->library[$identifier];
        }
    }
}