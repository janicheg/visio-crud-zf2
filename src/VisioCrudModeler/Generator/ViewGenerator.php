<?php
namespace VisioCrudModeler\Generator;

use Zend\Filter\Word\CamelCaseToSeparator;
use Zend\Filter\Word\UnderscoreToCamelCase;
/**
 * generator class for creating View templates
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ViewGenerator implements GeneratorInterface
{

    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;

    /**
     *
     * @var Zend\Filter\Word\UnderscoreToCamelCase
     */
    protected $underscoreToCamelCase;

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        $descriptor = $this->params->getParam("descriptor");
        if (! ($descriptor instanceof \VisioCrudModeler\Descriptor\ListGeneratorInterface)) {
            return;
        }

        $camelCaseToSeparator = new CamelCaseToSeparator('-');
        $moduleName = strtolower($this->params->getParam("moduleName"));

        @mkdir($this->moduleRoot() . "/view/", 0777);
        $viewClassFilePath = $this->moduleRoot() . "/view/" . strtolower($camelCaseToSeparator->filter($this->params->getParam("moduleName")));
        @mkdir($viewClassFilePath, 0777);

        foreach ($descriptor->listGenerator() as $name => $dataSet) {
            $className = $this->underscoreToCamelCase->filter($name);
            $viewName = strtolower($camelCaseToSeparator->filter($className));
            $viewFilePath = $this->moduleRoot() . "/view/" . $moduleName . "/" . $viewName;
            @mkdir($viewFilePath, 0777);
            $this->generateView($viewName, $viewFilePath, $moduleName);
            $this->console(sprintf('writing generated view for "%s" table', $viewName));
        }
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    protected function generateView($viewName, $viewFilePath, $moduleName)
    {
        $createViewPath = $viewFilePath . '/create.phtml';

        file_put_contents($createViewPath, $this->generateCreateView($moduleName));

        $updateViewPath = $viewFilePath . '/update.phtml';
        file_put_contents($updateViewPath, $this->generateUpdateView($moduleName));

        $readViewPath = $viewFilePath . '/read.phtml';
        file_put_contents($readViewPath, $this->generateReadView($moduleName, $viewName));

        $readViewPath = $viewFilePath . '/list.phtml';
        file_put_contents($readViewPath, $this->generateListView($moduleName, $viewName));

        $readViewPath = $viewFilePath . '/ajax-list.phtml';
        file_put_contents($readViewPath, $this->generateAjaxListView($moduleName, $viewName));
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    protected function generateCreateView($moduleName)
    {

        // $html = sprintf($this->codeLibrary()->get('form.standardConfigDescription');
        $html = sprintf($this->codeLibrary()->getFile('create.phtml'), $moduleName);

        return $html;
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    protected function generateUpdateView($moduleName)
    {
        $html = sprintf($this->codeLibrary()->getFile('update.phtml'), $moduleName);

        return $html;
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    protected function generateReadView($moduleName, $controllerName)
    {
        $html = sprintf($this->codeLibrary()->getFile('read.phtml'), $moduleName, $controllerName);

        return $html;
    }

    protected function generateListView($moduleName, $controllerName)
    {
        $html = sprintf($this->codeLibrary()->getFile('list.phtml'), $moduleName, $controllerName);

        return $html;
    }

    protected function generateAjaxListView($moduleName, $controllerName)
    {
        $html = sprintf($this->codeLibrary()->getFile('ajax-list.phtml'), $moduleName, $controllerName);

        return $html;
    }

    /**
     * returns code library
     *
     * @return CodeLibrary
     */
    protected function codeLibrary()
    {
        return $this->params->getParam('di')->get('VisioCrudModeler\Generator\CodeLibrary');
    }

    /**
     * proxy method for writing to console
     *
     * @param string $message
     */
    protected function console($message)
    {
        if ($this->params->getParam('console') instanceof \Zend\Console\Adapter\AdapterInterface) {
            $this->params->getParam('console')->writeLine($message);
        }
    }

    /**
     * returns absolute path to module directory
     *
     * @return string
     */
    protected function moduleRoot()
    {
        $modulesDirectory = $this->params->getParam('modulesDirectory');
        $moduleName = $this->params->getParam('moduleName');
        return $modulesDirectory . DIRECTORY_SEPARATOR . $moduleName;
    }

    public function __construct()
    {
        $this->underscoreToCamelCase = new UnderscoreToCamelCase();
    }
}
