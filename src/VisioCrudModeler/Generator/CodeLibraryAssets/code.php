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
    'standard.returnArray'=>'return array();'
);