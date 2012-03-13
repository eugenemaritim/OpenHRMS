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

class Model_Deduction extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_deduction';

    public function createDeduction($name, $description, $amount ,$job_group, $locked = null) {
        $row = $this->createRow();
        if($row) {
            $row->name = $name;
            $row->description = $description;
            $row->job_group = $job_group;
            $row->amount = $amount;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else  {
            return false;
        }
    }//createJobGroup

    public function updateDeduction($id, $name, $description, $amount, $job_group, $locked = null) {
        $row = $this->find($id)->current();
        if($row) {
            $row->name = $name;
            $row->description = $description;
            $row->job_group = $job_group;
            $row->amount = $amount;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//updateJobGroup

    public function deleteDeduction($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return false;
        }
    }//deleteJobGroup

    public function toggleStatus($id) {
        $row = $this->find($id)->current();
        if($row) {
            if($row->is_active == 1) {
                $row->is_active = 0;
            } else {
                $row->is_active = 1;
            }
            $row->save();
            return true;
        } else {
            return false;
        }
    }//toggleStatus

    public function getDeductions($job_group = null) {
        $select = $this->select();
        if(null != $job_group) {
            $select->where('job_group = ?', $job_group);
        }
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getDeduction($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }
}