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


class Zend_View_Helper_JobGroup extends Zend_View_Helper_Abstract {

     public function jobGroup() {        
        return $this;
    }

    public function getJobGroupName($id) {
        $model = new Model_JobGroup();
        $job_group = $model->getJobGroup($id);
        if($id == 0) {
            return 'None';
        } else {
            return $job_group->name; 
        }
    }
}