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
 * Company details
 +------------------------------------------------------------------------------
 */

class Model_Company extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_company';

    public function getCompanyDetails() {
        $select = $this->select();
        $select->where('id = ?', 1);
        $result = $this->fetchRow($select);
        return $result;
    }

    public function createUpdateCompany($id = 1, $name, $address, $tel) {
        $row = $this->find($id)->current();
        if($row) {
            $row->company_name = $name;
            $row->address = $address;
            $row->tel_no = $tel;
        } else {
            $row = $this->createRow();
            $row->company_name = $name;
            $row->address = $address;
            $row->tel_no = $tel;
        }
        $row->save();
        return $row;
    }
}