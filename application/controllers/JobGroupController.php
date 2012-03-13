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
 * Job Groups Management
 +------------------------------------------------------------------------------
 */

class JobGroupController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        
        $this->_model = new Model_JobGroup();
        $this->_helper->layout()->setLayout('admin');
    }
    
    public function indexAction() {
        $this->view->pageTitle = "List of Job Groups";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Job Groups";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $job_groups = $this->_model->getJobGroups();
        $this->view->job_groups = $job_groups;
    }

    public function addAction() {
        $this->view->pageTitle = "Add Job Group";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->createJobGroup($name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Job Group has been successfully created");
                    return $this->_redirect('/job-group/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating job group");
                    return $this->_redirect('/job-group/list');
            }
        }
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Job Group";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->updateJobGroup($id, $name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Job Group has been successfully updated");
                    return $this->_redirect('/job-group/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating job group");
                    return $this->_redirect('/job-group/list');
            }
        }
        $id = $request->getParam('id');
        $job_group = $this->_model->getJobGroup($id);
        $this->view->job_group = $job_group;
    }

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $this->_model->deleteJobGroup($id);
            if($delete) {
                 $this->getResponse()->setBody('RESULT_OK');
            } else {
                 $this->getResponse()->setBody('RESULT_ERROR');
            }//if
        }
    }

    public function lockAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

        $request = $this->getRequest();
        if($request->isPost()) {
            $id   = $request->getPost('id');
            $lock = $request->getPost('lock');

            $role = $this->_model->toggleStatus($id);
            if($role) {
                $this->getResponse()->setBody(1 - $lock);
            }
        }
    }


}