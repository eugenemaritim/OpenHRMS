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
 * departments
 +------------------------------------------------------------------------------
 */

class Model_Department extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_department';

    public function createDepartment($name, $desc, $locked = null) {
        $row = $this->createRow();
        if($row) {
            $row->name = $name;
            $row->description = $desc;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function updateDepartment($id, $name, $desc, $locked = null) {
        $row = $this->find($id)->current();
        if($row) {
            $row->name = $name;
            $row->description = $desc;
            if(null != $locked) {
                $row->is_active = $locked;
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }

    public function deleteDepartment($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return false;
        }
    }

    public function getDepartments($show_inactive = null) {
        $select = $this->select();
        if(null != $show_inactive) {
            $select->where('is_active = ?', 0);
        }
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getDepartment($id) {
        $select = $this->select();
        $select->where('dept_id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }

    public function toggleLock($id) {
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
    }
}