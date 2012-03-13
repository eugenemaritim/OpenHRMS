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
 * Job Titles Management
 +------------------------------------------------------------------------------
 */

class JobTitleController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_model = new Model_JobTitle();
        $this->_helper->layout()->setLayout('admin');
    }

    public function indexAction() {
        $this->view->pageTitle = "List of Job Titles";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Job Titles";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $job_titles = $this->_model->getJobTitles();
        $this->view->job_titles = $job_titles;
    }

    public function addAction() {
        $this->view->pageTitle = "Add Job Title";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->createJobTitle($name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Job Title has been successfully created");
                    return $this->_redirect('/job-title/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating job title");
                    return $this->_redirect('/job-title/list');
            }
        }
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Job Title";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $role = $this->_model->updateJobTitle($id, $name, $description, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Job Title has been successfully updated");
                    return $this->_redirect('/job-title/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating job title");
                    return $this->_redirect('/job-title/list');
            }
        }
        $id = $request->getParam('id');
        $job_title = $this->_model->getJobTitle($id);
        $this->view->job_title = $job_title;
    }

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $this->_model->deleteJobTitle($id);
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