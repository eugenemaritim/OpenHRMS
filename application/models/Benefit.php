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

class Model_Benefit extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_benefit';

    public function createBenefit($name, $description, $amount ,$job_group, $locked = null) {
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

    public function updateBenefit($id, $name, $description, $amount, $job_group, $locked = null) {
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

    public function deleteBenefit($id) {
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

    public function getBenefits($job_group = null) {
        $select = $this->select();
        if(null != $job_group) {
            $select->where('job_group = ?', $job_group);
        }
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getBenefit($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }

    /**
     * Get Benefits for a particular pay Grade
     */
    public function getBenefitsJobGroup($job_group_id) {
        $select = $this->select();
        $select->where('job_group = ?', $job_group_id);
        $result = $this->fetchAll($select);
        return $result;
    }
}