<?php

class Zend_View_Helper_Paginator extends Zend_View_Helper_Abstract
{
	/**
	 * Get this view helper instance
	 * 
	 * @return Core_View_Helper_Paginator
	 */
	public function paginator()
	{
		return $this;
	}
	
	/**
	 * Show slide paginator
	 * 
	 * @param Zend_Paginator $paginator
	 * @param array $options
	 * @return string
	 */
	public function slide($paginator, $options = array())
	{
		/**
		 * Don't show paginator if there's only one page
		 */
		if ($paginator->count() == 1) {
			return '';
		}
		$this->view->addScriptPath(APPLICATION_PATH . '/views/scripts');
		return $this->view->paginationControl($paginator, 
											'Sliding', '_partial/_pagination.phtml',
											array(
												'paginatorOptions' => $options,
											));
	}
	
	/**
	 * Generate link to item
	 * 
	 * @param int $pageIndex Page index of item
	 * @param string $label Label of link
	 * @param array $options Array consist of two options:
	 * - path
	 * - itemLink 
	 * @return string
	 */
	public function buildLink($pageIndex, $label, $options = array())
	{
		$url = $options['path'];
		$str = str_replace('%d', $pageIndex, $options['itemLink']);
		
		/**
		 * 10 is length of "javascript" (without ")
		 */
		if (0 == strncasecmp($options['itemLink'], 'javascript', 10)) {
			$url = $str;
		} else {
			$url = rtrim($url, '/') . '/' . $str;
		}
		return sprintf('<a href="%s">%s</a>', $url, $label);
	}
}
