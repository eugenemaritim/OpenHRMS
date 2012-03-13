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
 * Company details
 +------------------------------------------------------------------------------
 */

class CompanyController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $this->_model = new Model_Company();
        $this->_helper->layout()->setLayout('admin');
    }//init

    public function indexAction() {
        return $this->_forward('view');
    }

    public function viewAction() {
        $details = $this->_model->getCompanyDetails();
        $this->view->details = $details;
    }

    public function updateAction() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $name = $request->getPost('name');
            $address = $request->getPost('address');
            $tel_no = $request->getPost('tel_no');
            $company = $this->_model->createUpdateCompany($id = 1, $name, $address, $tel_no);
            if($company) {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("Company details have been updated");
                return $this->_redirect('/company');
            } else {
                $this->_helper->getHelper('FlashMessenger')
                                            ->addMessage("An Error Occured while updating Company Details");
                return $this->_redirect('/company');
            }
        } else {
            return $this->_redirect('/company');
        }
    }
}