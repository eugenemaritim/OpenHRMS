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
 * bank
 +------------------------------------------------------------------------------
 */

class BankController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $this->_helper->layout()->setLayout('admin');
        $this->_model = new Model_Bank();
    }

    public function indexAction() {
        return $this->_forward('list');
    }

    public function listAction() {
        $this->view->pageTitle = "List of Banks";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $banks = $this->_model->getBanks();
        $this->view->banks = $banks;
    }

    public function addAction() {
        $this->view->pageTitle = "Add Bank";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');            
            $bank = $this->_model->addBank($name);
            if($bank) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Bank has been successfully created");
                    return $this->_redirect('/bank/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while creating bank");
                    return $this->_redirect('/bank/list');
            }
        }
    }

    public function updateAction() {
        $this->view->pageTitle = "Update Bank";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $bank = $this->_model->updateBank($id, $name);
            if($name) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Bank was successfully updated");
                    return $this->_redirect('/bank');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An unexpected error occured while updating Bank");
                    return $this->_redirect('/bank');
            }
        }
        $id = $request->getParam('id');
        $bank = $this->_model->getBank($id);
        $this->view->bank = $bank;
    }

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $this->_model->deleteBank($id);
            if($delete) {
                 $this->getResponse()->setBody('RESULT_OK');
            } else {
                 $this->getResponse()->setBody('RESULT_ERROR');
            }//if
        }
    }
        
}