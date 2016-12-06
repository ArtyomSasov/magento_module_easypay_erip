<?php
class EasyPay_EasyPay_Model_Config extends Mage_Payment_Model_Config
{
	const EASYPAY_PAYMENT_PATH = 'payment/easypay_redirect/';

	public function getSuccessUrl()
	{
		return Mage::getBaseUrl() . 'easypay/payment/return/';
	}

	public function getCancelUrl()
	{
		return Mage::getBaseUrl() . 'easypay/payment/fail/';
	}

	public function getConfigData($path)
	{
		if (!empty($path)) {
			return Mage::getStoreConfig(self::EASYPAY_PAYMENT_PATH . $path);
		}
		return false;
	}

	public function getMerchantNo()
	{
		return $this->getConfigData('easypay_shop_id');
	}

	public function getExpires()
	{
		return $this->getConfigData('expires_easypay');
	}

	public function getDebug()
	{
		return $this->getConfigData('debug_easypay');
	}
	public function getWebKey()
	{
		return $this->getConfigData('web_key_easypay');
	}


}
