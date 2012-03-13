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
 * EMployee Payroll Deductions
 +------------------------------------------------------------------------------
 */
 

class Model_PayrollDeductions extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_payroll_deductions';

    public function insertPayrollDeduction($emp_id, $period_id, $deduction_name, $deduction_amount, $is_pre_tax_exempt, $type = null)  {
        $row = $this->createRow();
        if($row) {
            $row->emp_id = $emp_id;
            $row->period_id = $period_id;
            $row->deduction_name = $deduction_name;
            $row->deduction_amount = $deduction_amount;
            $row->is_pre_tax_exempt = $is_pre_tax_exempt;
            if(null != $type) {
                $row->type = $type;
            }
            $row->save();
            return $row;
        } else {
            return false;
        }
    }

     /**
     * Get employee deductions for a particular pay period
     *
     * @access public
     * @param
     * @return
     */
    public function getEmployeeDeductionsPayPeriod($emp_id, $period_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $select->where('period_id= ?', $period_id);
        $result = $this->fetchAll($select);
        return $result;
    }


    //--------------------------------------------------------------------------
    //  Get Co-operative deductions
    //--------------------------------------------------------------------------
    public function getCooperativeDeductions($period_id, $coop_id) {
        $select = $this->select();
        $select->where('period_id = ?', $period_id);
        $select->where('deduction_name = ?', $coop_id);
        $select->where('type = ?', 'coop');
        $result = $this->fetchAll($select);
        return $result;
    }
}