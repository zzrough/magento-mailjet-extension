<?php

/**
 * Magento Plugin
 *
 * @author		Mailjet
 * @link		http://www.mailjet.com/
 *
 */

class MJ_Customsmtp_Helper_Config extends Mage_Core_Helper_Abstract
{
	const XML_PATH_ENABLED =		'customsmtp/general/enabled';
	const XML_PATH_TEST =			'customsmtp/general/test';
	const XML_PATH_TEST_ADDRESS =	'customsmtp/general/test_address';
	const XML_PATH_SMTP_HOST =		'customsmtp/smtp/host';
	const XML_PATH_SMTP_PORT =		'customsmtp/smtp/port';
	const XML_PATH_SMTP_LOGIN =		'customsmtp/smtp/login';
	const XML_PATH_SMTP_PASSWORD =	'customsmtp/smtp/password';
	const XML_PATH_SMTP_SSL =		'customsmtp/smtp/ssl';
}