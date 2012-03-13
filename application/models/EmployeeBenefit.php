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
 * EMployee Benefits
 +------------------------------------------------------------------------------
 */

class Model_EmployeeBenefit extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_core_employee_benefits';

    public function updateBenefits($id, $benefits) {
        $select = $this->select();
        $select->where('emp_id = ?', $id);
        $result = $this->fetchAll($select);
        foreach ($result as $k) {
            $row = $this->find($k->id)->current();
            $row->delete();
        }
        foreach($benefits as $p) {
                $row = $this->createRow();
                $row->emp_id = $id;
                $row->benefit_id = $p;
                $row->save();
        }
        return true;
    }

    public function getEmployeeBenefits($emp_id) {
        $select = $this->select();
        $select->where('emp_id = ?', $emp_id);
        $result = $this->fetchAll($select);
        return $result;
    }
}