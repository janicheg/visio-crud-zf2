<?php
return array(
    'module.generatedConfigDescription'=>'This file is generated automatically for module "%s". Do not change its contents as it will be overwritten in next pass of generator run. instead use standard module config with array_merge_recursive to overwrite any generated values.',
    'module.standardConfigDescription'=>'This file is generated automatically for module "%s". If you want to overwrite any generated configuration for this module, this file is the place to do it.',
    'module.standardConfigBody'=>"return array_merge_recursive(require __DIR__ . 'config.generated.php',array(\n    // place your override here\n));",
    'module.moduleClassDescription'=>"Standard Module class",
    'module.onBootstrap.body'=>'$eventManager = $e->getApplication()->getEventManager();'."\n".'$moduleRouteListener = new ModuleRouteListener();'."\n".'$moduleRouteListener->attach($eventManager);',
    'module.onBootstrap.shortdescription'=>'standard bootstrap method',
    'module.onBootstrap.longdescription'=>'used to configure additional features, not available through module.config.php',
    'module.config.body'=>'return include __DIR__ . \'/config/module.config.php\';',
    'module.config.shortdescription'=>'loads module config',
    'module.config.longdescription'=>'standard method for loading module related configuration',
    'module.getAutoloaderConfig.body' =>'return array('."\n".
                                       '     \'Zend\Loader\StandardAutoloader\' => array('."\n".
                                       '         \'namespaces\' => array('."\n".
                                       '             __NAMESPACE__ => __DIR__ . \'/src/\' . __NAMESPACE__'."\n".
                                       '         )'."\n".
                                       '     )'."\n".
                                        ');',
    'module.getAutoloaderConfig.shortdescription'=>'autoloader configuration',
    'module.getAutoloaderConfig.longdescription'=>'used to load module related class',
    'standard.returnArray'=>'return array();',
    
    'model.getMethod.description'=>'Gets value for %s',
    'model.getMethodInt.body'=>'return intval($this->%s);',
    'model.getMethodFloat.body'=>'return floatval($this->%s);',
    'model.getMethodString.body'=>'return strval($this->%s);',
    'model.setMethod.description'=>'Sets value for %s',
    'model.setMethodInt.body'=>'$this->%s = $value;',
    'model.setMethodFloat.body'=>'$this->%s = $value;',
    'model.setMethodString.body'=>'$this->%s = $value;',
    'model.preInsert.description'=>'Override this method in child class if You need pre insert events',
    'model.postInsert.description'=>'Override this method in child class if You need post insert events',
    'model.preUpdate.description'=>'Override this method in child class if You need pre update events',
    'model.postUpdate.description'=>'Override this method in child class if You need post update events',
    'model.preDelete.description'=>'Override this method in child class if You need pre delete events',
    'model.postDelete.description'=>'Override this method in child class if You need post delete events',
    'model.generatedConfigDescription'=>'This file is generated automatically for model "%s". Do not change its contents as it will be overwritten in next pass of generator run. instead use standard model config with array_merge_recursive to overwrite any generated values.',
    'model.standardConfigDescription'=>'This file is generated automatically for model "%s". If you want to overwrite any generated configuration for this model, this file is the place to do it.',
    
);