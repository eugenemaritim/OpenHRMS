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
 * Employee benefits
 +------------------------------------------------------------------------------
 */

class BenefitController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
         $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if

        $this->_model = new Model_Benefit();
        $this->_helper->layout()->setLayout('admin');
    }

    public function indexAction() {
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Benefits";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        $query 	 = $request->getParam('query', '');
        $params  = array();
        if($query == '') {
            $benefits = $this->_model->getBenefits();
        } else {
            $params = Zend_Json::decode($query);
            foreach (array('job_group') as $key) {
		if (isset($params[$key]) && $params[$key] == '') {
			$params[$key] = null;
		}
            }
            $benefits = $this->_model->getBenefits($params['job_group']);
        }
        $this->view->benefits = $benefits;


        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(true);
        $this->view->job_groups = $job_groups;
        if ($query != '') {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();
		$content = $this->view->render('benefit/_filter.phtml');
		$this->getResponse()->setBody($content);
	}
    }

    public function addAction() {
        $this->view->pageTitle = "Add Benefit";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $description = $request->getPost('description');
            $amount = $request->getPost('amount');
            $job_group = $request->getPost('job_group');
            $lock   = $request->getPost('lock');
            $lock    = ($lock) ? 1 : 0;
            $benefit = $this->_model->createBenefit($name, $description, $amount, $job_group, $lock);
            if($benefit) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Benefit has been successfully created");
                    return $this->_redirect('/benefit/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating benefit");
                    return $this->_redirect('/benefit/list');
            }
        }
        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(true);
        $this->view->job_groups = $job_groups;
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Benefit";
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
            $role = $this->_model->updateBenefit($id, $name, $description, $amount, $job_group, $lock);
            if($role) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Benefit has been successfully updated");
                    return $this->_redirect('/benefit/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while updating benefit");
                    return $this->_redirect('/benefit/list');
            }
        }
        $id = $request->getParam('id');
        $benefit = $this->_model->getBenefit($id);
        $this->view->benefit = $benefit;
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


            $delete = $this->_model->deleteBenefit($id);
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