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
 * system roles
 +------------------------------------------------------------------------------
 */

class Model_Role extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_role';

    public function createRole($name, $desc, $locked = null) {
        $row = $this->createRow();
        if($row) {
            $row->name = $name;
            $row->description = $desc;
            $row->locked = $locked;
            $row->save();
            return true;
        } else {
            return false;
        }
    }

    public function getRoles() {
        $select = $this->select();
        $result = $this->fetchAll($select);
        return $result;
    }

    public function toggleLock($id) {
        $row = $this->find($id)->current();
        if($row) {
            if($row->locked == 1) {
                $row->locked = 0;
            } else {
                $row->locked = 1;
            }
            $row->save();
            return true;
        } else {
            return false;
        }
    }

    public function deleteRole($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return false;
        }
    }//deleteRole

    public function updateRole($id,$name, $desc, $locked = null) {
        $row = $this->find($id)->current();
        if($row) {
            $row->name = $name;
            $row->description = $desc;
            if(null != $locked) {
                $row->locked = $locked;
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//updateRole

    public function getRole($id) {
        $select = $this->select();
        $select->where('role_id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }//getRole



}