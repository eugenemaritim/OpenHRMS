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

class Model_EmployeeDeduction extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_deductions';

    public function updateDeductions($id, $deductions) {
        $select = $this->select();
        $select->where('emp_id = ?', $id);
        $result = $this->fetchAll($select);
        foreach ($result as $k) {
            $row = $this->find($k->id)->current();
            $row->delete();
        }
        foreach($deductions as $p) {
                $row = $this->createRow();
                $row->emp_id = $id;
                $row->deduction_id = $p;
                $row->save();
        }
        return true;
    }

     public function getEmployeeDeductions($emp_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getDeductionsJobGroup($job_group) {
        $select = $this->select();
        $select->where('job_group = ?', $job_group);
        $result = $this->fetchAll($select);
        return $result;
    }


   
}