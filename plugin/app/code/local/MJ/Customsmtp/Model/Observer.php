<?php

/**
 * Magento Plugin
 *
 * @author		Mailjet
 * @link		http://www.mailjet.com/
 *
 */

class MJ_Customsmtp_Model_Observer
{
	protected static $fields = array();

	public function sendTestMail($observer)
	{
		$data = $observer->getEvent()->getData('data_object')->getData();

		switch ($data ['field'])
		{
			case 'login':
				self::$fields['username'] = $data['value'];
			break;

			case 'password':
			case 'test':
			case 'test_address':
				self::$fields[$data['field']] = $data['value'];
			break;
		}

		if (self::$fields['test'] && 4 == count(self::$fields))
		{
			$configs = array (	array ('ssl://', 465),
								array ('tls://', 587),
								array ('', 587),
								array ('', 588),
								array ('tls://', 25),
								array ('', 25));

			$host = Mage::getStoreConfig(MJ_Customsmtp_Helper_Config::XML_PATH_SMTP_HOST);
			$connected = false;

			for ($i = 0; $i < count($configs); ++$i)
			{
				$soc = @fsockopen($configs[$i][0] . $host, $configs[$i][1], $errno, $errstr, 5);

				if ($soc)
				{
					fclose($soc);
					$connected = true;

					break;
				}
			}

			if ($connected)
			{
				if ('ssl://' == $configs[$i][0])
					Mage::getConfig()->saveConfig(MJ_Customsmtp_Helper_Config::XML_PATH_SMTP_SSL, 'SSL');
				elseif ('tls://' == $configs[$i][0])
					Mage::getConfig()->saveConfig(MJ_Customsmtp_Helper_Config::XML_PATH_SMTP_SSL, 'TLS');
				else
					Mage::getConfig()->saveConfig(MJ_Customsmtp_Helper_Config::XML_PATH_SMTP_SSL, '');

				Mage::getConfig()->saveConfig(MJ_Customsmtp_Helper_Config::XML_PATH_SMTP_PORT, $configs[$i][1]);

				Mage::getConfig()->reinit();
				Mage::app()->reinitStores();

				$to = self::$fields['test_address'];
				$from = Mage::getStoreConfig('trans_email/ident_general/email');

				$Mail = Mage::getModel('customsmtp/mail');

				$Mail->setBody(Mage::helper('customsmtp')->__('Your Mailjet configuration is ok!'));
				$Mail->setIsPlain(true);
				$Mail->setSubject(Mage::helper('customsmtp')->__('Your test mail from Mailjet'));

				$Mail->setFromName('Mailjet')
						->setFromEmail($from)
						->setReplyTo($from)
						->setToName($to)
						->setToEmail($to);

				$sender = Mage::getModel('customsmtp/email_template')->load(Mage::getStoreConfig(Mage::app()->getStore()->getId()));

				$sender->sendMail($Mail, self::$fields);
			}
			else
				throw new Exception(sprintf('Please contact Mailjet support to sort this out.<br /><br />%d - %s', $errno, $errstr));
		}
	}
}

?>