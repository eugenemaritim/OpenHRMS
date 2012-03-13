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
 * User Management
 +------------------------------------------------------------------------------
 */

class UserController extends Zend_Controller_Action {

    protected $_model;

    public function init() {
        $this->_model = new Model_User();
        $this->_helper->layout()->setlayout('admin');
    }//init

    public function indexAction() {
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Users";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();

        //get a list of all the roles
        $role_model = new Model_Role();
        $roles = $role_model->getRoles();
        $this->view->roles = $roles;

        $perPage = 15;
	$query 	 = $request->getParam('query', '');
	$params  = array();
	if ($query == '') {
		$pageIndex = 1;
		$params['pageIndex'] = $pageIndex;
                $users = $this->_model->getUsers();
	} else {
		$params = Zend_Json::decode($query);
		$pageIndex = $params['pageIndex'];

		foreach (array('username', 'role', 'status') as $key) {
			if (isset($params[$key]) && $params[$key] == '') {
				$params[$key] = null;
			}
		}
                $users = $this->_model->getUsers($params['username'], $params['role']);
	}

		/**
		 * Paginator
		 */
	$paginator = new Zend_Paginator($users);
	$paginator->setCurrentPageNumber($pageIndex);
	$paginator->setItemCountPerPage($perPage);

	$this->view->users =  $users;
	$this->view->currentUser =  Zend_Auth::getInstance()->getIdentity()->user_name;

	$this->view->paginator =  $paginator;
	$this->view->paginatorOptions = array(
			'path' 	   => '',
			'itemLink' => 'javascript: filterUsers(%d, ' . urlencode(Zend_Json::encode($params)) . ');',
		);

	if ($query != '') {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();
		$content = $this->view->render('user/_filter.phtml');
		$this->getResponse()->setBody($content);
	}
    }

    public function addAction() {
        $this->view->pageTitle = "Add New User";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();

        //get a list of all the roles
        $role_model = new Model_Role();
        $roles = $role_model->getRoles();
        $this->view->roles = $roles;

        if($request->isPost()) {
            $fullname  = $request->getPost('full_name');
            $username  = $request->getPost('username');
            $password  = $request->getPost('password');
            $password2 = $request->getPost('confirmPassword');
            $email 	   = $request->getPost('email');
            $roleId    = $request->getPost('role');

            if($password == $password2) {
                $user = $this->_model->createUser($fullname, $username, $password2, $email, $roleId);
                if($user) {
                    $this->_helper->getHelper('FlashMessenger')
					->addMessage("User has been successfully created");
                    return $this->_redirect('/user/list');
                } else {
                    $this->_helper->getHelper('FlashMessenger')
					->addMessage("An unexpected error occured while creating user");
                    return $this->_redirect('/user/list');
                }
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Password do not match, try again");
                    return $this->_redirect('/user/add');
            }
        }//if
    }//addAction

    public function updateAction() {
        $request = $this->getRequest();
	$userId  = $request->getParam('user_id');

        //get a list of all the roles
        $role_model = new Model_Role();
        $roles = $role_model->getRoles();
        $this->view->roles = $roles;

        $user  = $this->_model->getUser($userId);
        $this->view->user  = $user;

        if($request->isPost()) {
            $fullname = $request->getPost('full_name');
            $username = $request->getPost('username');
            $password = $request->getPost('confirmPassword');
            if($password != "") {
                $password = $password;
            } else {
                $password = null;
            }
            $email 	  = $request->getPost('email');
            $roleId   = $request->getPost('role');

            $user = $this->_model->updateUser($userId, $fullname, $username, $email, $roleId, $password);
            if($user) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("User $user->full_name has been successfully updated");
                return $this->_redirect('/user/list');
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An unexpected error occured while updating $user->full_name");
                return $this->_redirect('/user/list');
            }
        }
    }//updateAction

    public function deleteAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');

            $delete = $this->_model->deleteUser($id);
            if($delete) {
                $this->getResponse()->setBody('RESULT_OK');
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deleteAction

    public function changePasswordAction() {
        $this->view->pageTitle = "Change Password";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');


        $this->_helper->layout()->setLayout('admin');
        $request = $this->getRequest();
        $user = Zend_Auth::getInstance()->getIdentity();
        if($request->isPost()) {
            $password = $request->getPost('password');
            $user = $this->_model->changePassword($user->id, $password);
            if($user) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Your password has been changed successfully");
                return $this->_redirect('/user/change-password');
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An unexpected error occured while changing password");
                return $this->_redirect('/user/change-password');
            }
        }
    }//changePasswordAction

    

    public function checkAction() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request   = $this->getRequest();
	$checkType = $request->getParam('check_type');
	$original  = $request->getParam('original');
	$value 	   = $request->getParam($checkType);

        $result = false;
	if ($original == null || ($original != null && $value != $original)) {
		$result = $this->_model->userExists($checkType, $value);
	}
	($result == true) ? $this->getResponse()->setBody('false')
						  : $this->getResponse()->setBody('true');
    }//checkAction

    public function changeStatusAction() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id	= $request->getPost('id');
            $status = $request->getPost('status');

            if ($id != Zend_Auth::getInstance()->getIdentity()->id) {
                $user = $this->_model->toggleStatus($id);
                if($user) {
                    $this->getResponse()->setBody(1 - $status);
                }
            }
        }
    }//changeStatusAction

    //--------------------------------------------------------------------------
    //  Authentication
    //--------------------------------------------------------------------------

    public function loginAction() {
        $this->_helper->layout()->setLayout('auth');
        $request = $this->getRequest();
        $this->view->pageTitle = "Login";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');

        //check if user is already logged in
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
            return $this->_redirect('/');
        }//if

        if($request->isPost()) {
            $username = $request->getPost('username');
            $password = $request->getPost('password');
            $hash = md5($password);
            $db = Zend_Db_Table::getDefaultAdapter();
            $adapter = new Zend_Auth_Adapter_DbTable($db,'tc_core_users', 'user_name', 'password');
            $adapter->setIdentity($username);
            $adapter->setCredential($hash);
            $result = $adapter->authenticate();
            if($result->isValid()) {
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $storage->write($adapter->getResultRowObject(array('full_name', 'user_name', 'id', 'role_id')));
                return $this->_redirect('/');
            } else {
                $this->_helper->getHelper('FlashMessenger')
						->addMessage("Authentication login failure, try again");
                return $this->_redirect('/user/login');
            }
        }

    }//loginAction

    public function logoutAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$auth = Zend_Auth::getInstance();
	if ($auth->hasIdentity()) {

            Zend_Session::destroy(false, false);
            $auth->clearIdentity();
	}
	$this->_redirect($this->view->staticUrl());
    }//logoutAction

    
}