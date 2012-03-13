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

class Zend_View_Helper_Department extends Zend_View_Helper_Abstract {

    protected $_model;

    public function department() {
        $this->_model = new Model_Department();
        return $this;
    }

    public function getDepartmentName($id) {
        $model = new Model_Department();
        $dept = $model->getDepartment($id);
        return $dept->name; 
    }
}