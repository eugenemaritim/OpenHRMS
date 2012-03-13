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
 * Application dashboard
 +------------------------------------------------------------------------------
 */
class DashboardController extends Zend_Controller_Action {

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }
        $this->_helper->layout()->setLayout('admin');
    }//init
    
    public function indexAction() {
        $this->view->pageTitle = "Dashboard";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
    }//indexAction


}