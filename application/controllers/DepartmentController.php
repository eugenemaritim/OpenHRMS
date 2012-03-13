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
 * Departments Management
 +------------------------------------------------------------------------------
 */
class DepartmentController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_model = new Model_Department();
        $this->_helper->layout()->setLayout('admin');        
    }

    public function indexAction() {
        $this->view->pageTitle = "List of Departments";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        return $this->_forward('list');
    }

    public function listAction() {
        $this->view->pageTitle = "List of Departments";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $departments = $this->_model->getDepartments();
        /*$user_model = new Model_User();
        foreach($departments as $department) {
            $department->members = $user_model->getUsersPerDepartment($department->dept_id);
        } */
        $this->view->departments = $departments;
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
            $role = $this->_model->createDepartment($name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Department has been successfully created");
                    return $this->_redirect('/department/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating department");
                    return $this->_redirect('/department/list');
            }
        }
        
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Department";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('dept_id');
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->updateDepartment($id, $name, $description);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Department has been successfully updated");
                    return $this->_redirect('/department/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while updating department");
                    return $this->_redirect('/department/list');
            }
        }
        $id = $request->getParam('id');
        $dept = $this->_model->getDepartment($id);
        $this->view->dept = $dept;
    }

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

            //@todo check num of users

            /*
            $user_model = new Model_User();
            $count = $user_model->getUsersPerRole($id);
            if($count == 0) {
                
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
             * 
             */
            $delete = $this->_model->deleteDepartment($id);
            if($delete) {
                $this->getResponse()->setBody('RESULT_OK');
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deleteAction
}