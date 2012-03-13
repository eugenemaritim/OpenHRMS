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
 * Error Handler
 +------------------------------------------------------------------------------
 */


class ErrorController extends Zend_Controller_Action {

    /**
     * errorAction() is the action that will be called by the "ErrorHandler"
     * plugin.  When an error/exception has been encountered
     * in a ZF MVC application (assuming the ErrorHandler has not been disabled
     * in your bootstrap) - the Errorhandler will set the next dispatchable
     * action to come here.  This is the "default" module, "error" controller,
     * specifically, the "error" action.  These options are configurable, see
     * {@link http://framework.zend.com/manual/en/zend.controller.plugins.html#zend.controller.plugins.standar
     *
     * @return void
     */
    public function errorAction()
    {
        $this->_helper->layout->disableLayout();
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Requested page could not be found';
                if(APPLICATION_ENV != 'development') {
                    $this->_helper->viewRenderer('404');  
                }                
                break;

            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'An Application error has occured';
                if(APPLICATION_ENV != 'development') {
                    $this->_helper->viewRenderer('500');  
                }   
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }

    public function nopermissionAction() {
        $this->_helper->layout->disableLayout();
        $this->view->title = "No permission";
    }
}