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
 * bank model
 +------------------------------------------------------------------------------
 */

class Model_Bank extends Zend_Db_Table_Abstract {

    protected $_name = 'tc_bank';

    public function addBank($name) {
        $row = $this->createRow();
        if($row) {
            $row->name = $name;
            $row->save();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function updateBank($id, $name) {
        $row = $this->find($id)->current();
        if($row) {
            $row->name = $name;
            $row->save();
            return $row;
        } else {
            return false;
        }
    }


    public function deleteBank($id) {
        $row = $this->find($id)->current();
        if($row) {
            $row->delete();
            return true;
        } else {
            return FALSE;
        }
    }

    public function getBanks($name = null) {
        $select = $this->select();
        if(null != $name) {
            $select->where('name LIKE ?', $name);
        }
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getBank($id) {
        $select = $this->select();
        $select->where('id = ?', $id);
        $result = $this->fetchRow($select);
        return $result;
    }

}