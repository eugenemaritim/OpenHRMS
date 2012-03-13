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


class Zend_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract  {

    public function flashMessenger() {
        $this->view->addScriptPath(Zend_Layout::getMvcInstance()->getLayoutPath());	

	$flashMsgHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
	$this->view->assign('messages', $flashMsgHelper->getMessages());

	return $this->view->render('_partial/_messages.phtml');
    }
}