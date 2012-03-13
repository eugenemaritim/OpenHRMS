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
 * System Roles
 +------------------------------------------------------------------------------
 */

class RoleController extends Zend_Controller_Action {

    protected  $_model;

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_helper->layout()->setLayout('admin');
        $this->_model = new Model_Role();
        
    }

    public function listAction() {
        $this->view->pageTitle = "Roles";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $user_model = new Model_User();        
        $roles = $this->_model->getRoles();
        foreach($roles as $role) {
            $role->users = $user_model->getUsersPerRole($role->role_id);
        }
        $this->view->roles  = $roles;
    }

    public function addAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->createRole($name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Role has been successfully created");
                    return $this->_redirect('/role/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating group");
                    return $this->_redirect('/role/list');
            }
        }
    }//addAction


    public function lockAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

        $request = $this->getRequest();
        if($request->isPost()) {
            $id   = $request->getPost('id');
            $lock = $request->getPost('lock');

            $role = $this->_model->toggleLock($id, $lock);
            if($role) {
                $this->getResponse()->setBody(1 - $lock);
            }
        }
    }//lockAction

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');

            //check if there are no users
            $user_model = new Model_User();
            $count = $user_model->getUsersPerRole($id);
            if($count == 0) {
                $delete = $this->_model->deleteRole($id);
                if($delete) {
                    $this->getResponse()->setBody('RESULT_OK');
                } else {
                    $this->getResponse()->setBody('RESULT_ERROR');
                }
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deleteAction

    public function updateAction() {
        $this->view->pageTitle = "Update role";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        $id = $request->getParam('id');
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->updateRole($id, $name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Role has been successfully updated");
                    return $this->_redirect('/role/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while updating group");
                    return $this->_redirect('/role/list');
            }
        }
        $role = $this->_model->getRole($id);
        $this->view->role = $role;
    }//updateAction
}