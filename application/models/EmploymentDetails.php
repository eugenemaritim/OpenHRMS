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
 * Employee Details
 +------------------------------------------------------------------------------
 */

class Model_EmploymentDetails extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employment_details';
    protected $_dependentTables = array('Model_EmployeeBenefit');

    public function updateEmploymentDetails($emp_id, $pay_type, $pay_amt, $std_day, $std_prd, $time_worked, $pay_ot, $ot_rate, $ded_nssf, $ded_nhif, $nssf_no, $nhif_no) {
        if($this->find($emp_id)->current()) {
            $row = $this->find($emp_id)->current();
        } else {
            $row = $this->createRow();
        }
        $row->emp_id = $emp_id;
        $row->payment_type = $pay_type;
        $row->payment_amount = $pay_amt;
        $row->std_working_hours_day = $std_day;
        $row->std_working_hours_period = $std_prd;
        $row->time_worked = $time_worked;
        $row->pay_overtime = $pay_ot;
        $row->overtime_rate = $ot_rate;
        $row->deduct_nssf = $ded_nssf;
        $row->deduct_nhif = $ded_nhif;
        $row->nssf_no = $nssf_no;
        $row->nhif_no = $nhif_no;
        $row->save();
        return $row;
    }


    public function getEmploymentDetails($emp_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $result = $this->fetchRow($select);
        return $result;
    }
}