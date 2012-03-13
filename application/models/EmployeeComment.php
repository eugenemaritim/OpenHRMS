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
 * Employee Comments
 +------------------------------------------------------------------------------
 */

class Model_EmployeeComment extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_comments';

    public function addComment($emp_id, $by_id, $remark ) {
        $row = $this->createRow();
        if($row) {
            $row->emp_id = $emp_id;
            $row->by_id = $by_id;
            $row->comment_date = date('Y-m-d');
            $row->remark = $remark;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//addComment


    public function updateComment() {
        
    }

    public function deleteComment($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return false;
        }
    }//deleteComment

    public function getComments($emp_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $select->order('comment_date DESC');
        $result = $this->fetchAll($select);
        return $result;
    }
}