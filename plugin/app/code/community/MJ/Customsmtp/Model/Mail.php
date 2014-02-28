<?php

/**
 * Magento Plugin
 *
 * @author		Mailjet
 * @link		http://www.mailjet.com/
 *
 */

class MJ_Customsmtp_Model_Mail extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('customsmtp/mail');
	}

	public function _beforeSave()
	{
		if (!$this->getDate())
			$this->setDate(now());

		return parent::_beforeSave();
	}
}