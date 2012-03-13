<?php

// +----------------------------------------------------------------------
// | OpenHRMS | Open Source Human Resource Management and Information System
// +----------------------------------------------------------------------
// | Copyright (c) 2012 http://www.openhrms.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Eugene Maritim <maritim@openhrms.net>
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------

/**
 +------------------------------------------------------------------------------
 * Application Bootstrap configuration
 +------------------------------------------------------------------------------
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    //------------------------------------
    // View config
    //
    // @access protected
    //------------------------------------
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getContainer()->view;
        $view->doctype('HTML5');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=' . strtolower($view->getEncoding()));
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle($this->getOption('title'));
    }//_initDoctype


    //------------------------------------
    // Autoload class namespaces
    //
    // @access protected
    //------------------------------------
    protected function _initAutoload() {
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
           'basePath'=> APPLICATION_PATH,
           'namespace'=>'',
           'resourceTypes'=> array(
               'model'=>array(
                   'path'=>'models/',
                   'namespace'=>'Model_'
               ),
               'form'=>array(
                   'path'=>'forms/',
                   'namespace'=>'Form_'
               ),
           )
        ));
        return $autoLoader;
    }//_initAutoload
    
}