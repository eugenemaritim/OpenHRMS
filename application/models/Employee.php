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
 * Employee Management
 +------------------------------------------------------------------------------
 */

class Model_Employee extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee';

    public function createEmployee($surname, $other_name, $dept_id, $job_group, $job_title) {
        $row = $this->createRow();
        if($row) {
            $row->surname = $surname;
            $row->other_name = $other_name;
            $row->dept_id = $dept_id;
            $row->job_group = $job_group;
            $row->job_title = $job_title;
            $row->date_of_employment = date('Y-m-d');
            $row->save();
            return $row;
        } else {
            return false;
        }
    }

    public function getEmployee($id) {
        $select = $this->select();
        $select->where('emp_id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }

    public function getEmployees($emp_id = null, $job_title = null, $dept_id = null, $job_group = null) {
        $select = $this->select();
        if(null != $emp_id) {
            $select->where('emp_id = ?', $emp_id);
            $result = $this->fetchAll($select);
            return $result;
        }
        if(null != $dept_id) {
            $select->where('dept_id = ?', $dept_id);
        }
        if(null != $job_title) {
            $select->where('job_title = ?', $job_title);
        }
        if(null != $job_group) {
            $select->where('job_group = ?', $job_group);
        }
        $result = $this->fetchAll($select);
        return $result;
    }

    /**
     * Paginated list of employees
     *
     * @access public
     */
    public function findEmployees($last_name = null, $dept = null, $job_group = null, $job_title = null) {
        $select = $this->select();
        if(null != $last_name) {
            $select->where('surname LIKE ?', $last_name);
        }
        if(null != $dept) {
            $select->where('dept_id = ?', $dept);
        }

        if(null != $job_title) {
            $select->where('job_title = ?', $job_title);
        }

        if(null != $job_group) {
            $select->where('job_group = ?', $job_group);
        }

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        return $adapter;
    }//findEmployees

    public function getEmployeeList($dept_id = null, $bank_id = null, $job_group = null, $job_title = null) {
        $select = $this->select();
        if(null != $dept_id) {
            $select->where('dept_id = ?', $dept_id);
        }
        if(null != $bank_id) {
            $select->where('bank_id = ?', $bank_id);
        }
        if(null != $job_group) {
            $select->where('job_group = ?', $job_group);
        }

        if(null != $job_title) {
            $select->where('job_title = ?', $job_title);
        }
        $result = $this->fetchAll($select);
        return $result;
    }//getEmployeeList

     public function getActiveEmployees() {
        $select = $this->select();
        $select->where('is_active = ?', 0);
        $result = $this->fetchAll($select);
        return $result;
    }//getActiveEmployees

}
