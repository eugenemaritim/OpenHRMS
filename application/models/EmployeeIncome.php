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
 * Employee Incomes
 +------------------------------------------------------------------------------
 */

class Model_EmployeeIncome extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_payroll_income';

    public function insertPeriodIncomeDetails($emp_id, $period_id, $name, $amount, $type, $is_tax_exempt) {
        $row = $this->createRow();
        if($row) {
            $row->emp_id = $emp_id;
            $row->period_id = $period_id;
            $row->income_name = $name;
            $row->income_amount = $amount;
            $row->type = $type;
            $row->is_tax_exempt = $is_tax_exempt;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//insertPeriodIncomeDetails
    
    public function updatePeriodIncomeDetails($id, $name, $amount, $is_tax_exempt) {
        $row = $this->find($id)->current();
        if($row) {
            $row->income_name = $name;
            $row->income_amount = $amount;
            $row->is_tax_exempt = $is_tax_exempt;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }//updatePeriodIncomeDetails

    public function getEmployeePeriodIncomeDetails($emp_id, $period_id, $type = null) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $select->where('period_id = ?', $period_id);
        if(null != $type) {
            $select->where('type = ?', $type);
        }
        $result = $this->fetchAll($select);
        return $result;
    }//getEmployeePeriodIncomeDetails

    public function deleteEmployeeIncome($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return false;
        }
    }


    public function getIncome($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }//getIncome

}