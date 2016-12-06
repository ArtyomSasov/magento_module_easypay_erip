<?php
class EasyPay_Erip_Model_Config extends Mage_Payment_Model_Config
{
	const ERIP_PAYMENT_PATH = 'payment/erip_redirect/';

	public function getSuccessUrl()
	{
		return Mage::getBaseUrl() . 'erip/payment/return/';
	}

	public function getCancelUrl()
	{
		return Mage::getBaseUrl() . 'erip/payment/fail/';
	}

	public function getConfigData($path)
	{
		if (!empty($path)) {
			return Mage::getStoreConfig(self::ERIP_PAYMENT_PATH . $path);
		}
		return false;
	}

	public function getMerchantNo()
	{
		return $this->getConfigData('shop_id_erip');
	}

	public function getExpires()
	{
		return $this->getConfigData('expires_erip');
	}

	public function getWebKey()
	{
		return $this->getConfigData('web_key_erip');
	}


}
