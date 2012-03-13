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
 * Employee Deductions
 +------------------------------------------------------------------------------
 */

class DeductionController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
         $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if

        $this->_model = new Model_Deduction();
        $this->_helper->layout()->setLayout('admin');
    }

    public function indexAction() {
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Deductions";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        $query 	 = $request->getParam('query', '');
        $params  = array();
        if($query == '') {
            $deductions = $this->_model->getDeductions();
        } else {
            $params = Zend_Json::decode($query);
            foreach (array('job_group') as $key) {
		if (isset($params[$key]) && $params[$key] == '') {
			$params[$key] = null;
		}
            }
            $deductions = $this->_model->getDeductions($params['job_group']);
        }
        $this->view->deductions = $deductions;


        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(true);
        $this->view->job_groups = $job_groups;
        if ($query != '') {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();
		$content = $this->view->render('deduction/_filter.phtml');
		$this->getResponse()->setBody($content);
	}
    }

    public function addAction() {
        $this->view->pageTitle = "Add a Deduction";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $amount = $request->getPost('amount');
            $job_group = $request->getPost('job_group');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $deduction = $this->_model->createDeduction($name, $description, $amount, $job_group, $lock);
            if($deduction) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Deduction has been successfully created");
                    return $this->_redirect('/deduction/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating deduction");
                    return $this->_redirect('/deduction/list');
            }
        }
        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(true);
        $this->view->job_groups = $job_groups;
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Deduction";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $amount = $request->getPost('amount');
            $job_group = $request->getPost('job_group');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $deduction = $this->_model->updateDeduction($id, $name, $description, $amount, $job_group, $lock);
            if($deduction) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Deduction has been successfully updated");
                    return $this->_redirect('/deduction/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while updating deduction");
                    return $this->_redirect('/deduction/list');
            }
        }
        $id = $request->getParam('id');
        $deduction = $this->_model->getDeduction($id);
        $this->view->deduction = $deduction;
        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(true);
        $this->view->job_groups = $job_groups;
    }

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $this->_model->deleteDeduction($id);
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