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



class Zend_View_Helper_User extends Zend_View_Helper_Abstract {

    protected $_model;

    public function user() {
        $this->_model = new Model_User();
        return $this;
    }

    public function getUserByName($id) {
        $user = $this->_model->getUser($id);
        return $user->full_name;
    }
}