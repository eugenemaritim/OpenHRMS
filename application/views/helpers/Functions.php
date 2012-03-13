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


class Zend_View_Helper_Functions extends Zend_View_Helper_Abstract {

    public function functions() {
        return $this;
    }

    /**
     * Get the month Name of pay period
     *
     * @access public
     * @param $month The number of the month
     * @param $format Short form or long form of month name e.g Jan=Short, January=Long, default is long
     * @return $month
     */
    public function getMonthName($id, $format = null) {
        switch($id) {
            case 1:
                $name = 'January';
                $short_name = 'Jan';
                break;
            case 2:
                $name = 'February';
                $short_name = 'Feb';
                break;
            case 3:
                $name = 'March';
                $short_name = 'Mar';
                break;
            case 4:
                $name = 'April';
                $short_name = 'Apr';
                break;
            case 5:
                $name = 'May';
                $short_name = 'May';
                break;
            case 6:
                $name = 'June';
                $short_name = 'Jun';
                break;
            case 7:
                $name = 'July';
                $short_name = 'Jul';
                break;
            case 8:
                $name = 'August';
                $short_name = 'Aug';
                break;
            case 9:
                $name = 'September';
                $short_name = 'Sep';
                break;
            case 10;
                $name = 'October';
                $short_name = 'Oct';
                break;
            case 11:
                $name = 'November';
                $short_name = 'Nov';
                break;
            case 12:
                $name = 'December';
                $short_name = 'Dec';
                break;
        }//switch
        if(null != $format) {
            return $short_name;
        } else {
            return $name;
        }
    }
}