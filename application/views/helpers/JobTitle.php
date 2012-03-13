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



class Zend_View_Helper_JobTitle extends Zend_View_Helper_Abstract {

    protected $_model;

    public function jobTitle() {
        $this->_model = new Model_JobTitle();
        return $this;
    }

    public function getJobTitleName($id) {
        $job_title = $this->_model->getJobTitle($id);
        return $job_title->name;
    }
}