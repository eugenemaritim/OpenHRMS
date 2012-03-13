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
 * Payroll Income
 +------------------------------------------------------------------------------
 */

class Model_PayrollIncome extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_payroll_income';

    public function setIncome($emp_id, $period_id, $type, $name, $amount, $is_tax_exempt, $income_id = null) {
        if(null != $income_id) {
            $row = $this->find($income_id)->current();
        } else {
            $row = $this->createRow();
        }
        if($row) {
            $row->emp_id = $emp_id;
            $row->period_id = $period_id;
            $row->type = $type;
            $row->name = $name;
            $row->amount = $amount;
            $row->is_tax_exempt = $is_tax_exempt;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }

    public function getIncome($emp_id, $period_id, $type) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $select->where('period_id = ?', $period_id);
        $select->where('type = ?', $type);
        $result = $this->fetchAll($select);
        return $result;
    }
}