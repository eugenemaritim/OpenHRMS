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
 * Pay Periods
 +------------------------------------------------------------------------------
 */

class Model_PayPeriod extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_pay_period';

    public function closePayPeriod() {
        $select = $this->select();
        $select->where('status = ?', 'current');
        $result = $this->fetchRow($select);
        $row = $this->find($result->id)->current();
        if($row) {
            $row->status = 'closed';
            $row->closed_by = 'Eugene Maritim';
            $row->close_date = date('Y-m-i h:m:s');
            $row->save();
            $this->createNewPayPeriod();
            return $row;
        } else {
            return false;
        }
        
    }
    protected function createNewPayPeriod() {
        $row = $this->createRow();
        $next_month = $year = 0;
        if($row) {
            if(date('m' == 12)) {
                $next_month = 1;
                $year = date('Y') + 1;
            } else {
                $next_month = date('m') + 1;
                $year = date('Y');
            }
        }
        $row->period_year = $year;
        $row->period_month = $next_month;
        $row->status = 'current';
        $row->save();
        return;
    }

    public function getPayPeriod($year, $month) {
        $select = $this->select();
        $select->where('period_year = ?', $year);
        $select->where('period_month = ?', $month);
        $result = $this->fetchRow($select);
        return $result->id;
    }

    public function getCurrentPayPeriod() {
        $select = $this->select();
        $select->where('status = ?', 'current');
        $result = $this->fetchRow($select);
        return $result;
    }
    
}