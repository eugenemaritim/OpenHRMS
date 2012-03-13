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
 * Job Titles
 +------------------------------------------------------------------------------
 */

class Model_JobTitle extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_job_title';

    public function createJobTitle($name, $description, $locked = null) {
        $row = $this->createRow();
        if($row) {
            $row->name = $name;
            $row->description = $description;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else  {
            return false;
        }
    }//createJobGroup

    public function updateJobTitle($id, $name, $description, $locked = null) {
        $row = $this->find($id)->current();
        if($row) {
            $row->name = $name;
            $row->description = $description;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//updateJobGroup

    public function deleteJobTitle($id) {
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

    public function getJobTitles() {
        $select = $this->select();
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getJobTitle($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }
}