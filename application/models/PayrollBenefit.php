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
 * Employee Payroll Benefits
 +------------------------------------------------------------------------------
 */

class Model_PayrollBenefit extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_payroll_benefits';

    public function insertPayrollBenefit($emp_id, $period_id, $benefit_name, $benefit_amount, $is_pre_tax_exempt)  {
        $row = $this->createRow();
        if($row) {
            $row->emp_id = $emp_id;
            $row->period_id = $period_id;
            $row->benefit_name = $benefit_name;
            $row->benefit_amount = $benefit_amount;
            $row->is_tax_exempt = $is_pre_tax_exempt;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }

     /**
     * Get employee benefits for a particular pay period
     *
     * @access public
     * @param
     * @return
     */
    public function getEmployeeBenefitsPayPeriod($emp_id, $period_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $select->where('period_id= ?', $period_id);
        $result = $this->fetchAll($select);
        return $result;
    }
}