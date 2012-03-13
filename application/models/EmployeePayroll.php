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
 * Employee Payroll
 +------------------------------------------------------------------------------
 */

class Model_EmployeePayroll extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_payroll';

    public function insertPayrollDetails($emp_id, $period_id, $monthly_pay) {
        $row = $this->createRow();
        if($row) {
            $row->emp_id = $emp_id;
            $row->period_id = $period_id;
            $row->monthly_pay = $monthly_pay;
            //$row->paye = $paye;
            $row->save();
            return $row;
        } else {
            return FALSE;
        }
    }


    public function getEmployeesInPayPeriod($period_id) {
        $select = $this->select();
        $select->where('period_id = ?', $period_id);
        $result = $this->fetchAll($select);
        return $result;
    }
}