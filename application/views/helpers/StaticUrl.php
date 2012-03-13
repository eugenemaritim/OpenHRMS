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



class Zend_View_Helper_StaticUrl {

    public function staticUrl($append = null) {
        // Get the baseUrl
        $url = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();

        // Append the supplied url
        if ( $append !== null ) {
            $url = $url . '/' . trim((string)$append, '/\\');
        }

        // Return
        return $url;
    }//staticUrl
}