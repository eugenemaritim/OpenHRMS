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
 * User Management
 +------------------------------------------------------------------------------
 */

class Model_User extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_users';

    public function createUser($fullname, $username, $password, $email, $role_id, $is_active = null) {
        $row = $this->createRow();
        if($row) {
            $row->full_name = $fullname;
            $row->user_name = $username;
            $row->password = md5($password);
            $row->email = $email;
            $row->role_id = $role_id;
            $row->created_date = date('Y-m-d H:i:s');
            if(null != $is_active) {
                 $row->is_active = $is_active;
            }
            $row->save();
            return $row;
        }else {
            return false;
        }
    }//createUser


    public function updateUser($id, $fullname, $username, $email, $role_id, $password = null) {
        $row = $this->find($id)->current();
        if($row) {
            $row->full_name = $fullname;
            $row->user_name = $username;
            $row->email = $email;
            $row->role_id = $role_id;
            if(null != $password) {
                 $row->password = md5($password);
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//updateUser

    public function deleteUser($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return FALSE;
        }
    }//deleteUser

    public function changePassword($id, $password) {
        $row = $this->find($id)->current();
        if($row) {
            $row->password = md5($password);
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//changePassword

    public function userExists($checktype, $value) {
        $select = $this->select();
        if($checktype == "username") {
            $checktype = 'user_name';
        }
        $select->where($checktype . ' = ?', $value);
        $result = $this->fetchRow($select);
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }//userExists

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

    public function getUsers($username = null, $role = null) {
        $select = $this->select();
        if(null != $role) {
            $select->where('role_id = ?', $role);
        }
        if(null != $username) {
            $select->where('user_name LIKE ?', $username);
        }

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        return $adapter;
    }//getUsers

    public function getUser($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }//getUser

    public function getUsersPerRole($role_id) {
        $select = $this->select();
        $select->where('role_id = ?', $role_id);
        $result = $this->fetchAll($select);
        return count($result);
    }

    public function getEmployeesPerDepartment($dept_id) {
        $select = $this->select();
        $select->where('dept_id = ?', $dept_id);
        $result = $this->fetchAll($select);
        return count($result);
    }//getUsersPerDepartment

    
}
