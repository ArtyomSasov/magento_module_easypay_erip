<?php
class EasyPay_EasyPay_Model_Redirect extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'easypay_redirect';

	//Особенности платёжного метода
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = false;
	protected $_canUseForMultishipping  = false;

	//Процедура инициализации
	//@param string $paymentAction
	//@param Varien_Object $stateObject
	public function initialize($paymentAction, $stateObject)
	{	
		$state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
		$stateObject->setState($state);
		$stateObject->setStatus(true);
		$stateObject->setIsNotified(false);
	}

	//Проверка электронной подписи
	//@return bool
    public function validateRequest($post_params){

        $config = Mage::getModel('erip/config');

        if (!isset($post_params['order_mer_code']) ||
            !isset($post_params['sum']) ||
            !isset($post_params['mer_no']) ||
            !isset($post_params['card']) ||
            !isset($post_params['purch_date'])
        ) {
            return 'error';
        }
        $hash = md5($post_params['order_mer_code'].
            $post_params['sum'].
            $post_params['mer_no'].
            $post_params['card'].
            $post_params['purch_date'].
            $config->getWebKey());
        return $post_params['notify_signature'] == $hash;

    }

	//Возвращает url переадресации (используется после подтверждения заказа из корзины)
	public function getOrderPlaceRedirectUrl()
	{
		  return Mage::getUrl('easypay/payment/redirect', array('_secure' => true));
	}

	//Возвращает массив полей для отправки на сайт EasyPay
	//@return array
	public function getRedirectFormFields()
	{
		$result = array();
		$session = Mage::getSingleton('checkout/session');
		$config = Mage::getModel('easypay/config');
		$order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());

		if (!$order->getId()) {
			return $result;
		}

		$result['EP_MerNo'] = $config->getMerchantNo();
		$result['EP_Sum'] = $order->getGrandTotal();
		$result['EP_OrderNo'] = $order->getIncrementId();
		$result['EP_Expires'] = $config->getExpires();
		$result['EP_Success_URL'] = $config->getSuccessUrl();
		$result['EP_Cancel_URL'] = $config->getCancelUrl(); 
		$result['EP_Debug'] = $config->getDebug();
		$result['EP_URL_Type'] = 'link';
		$result['EP_Encoding'] = 'utf-8';
		$result['EP_Comment']  = 'Покупки в '. Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
		$result['EP_OrderInfo'] = 'Заказ от ' . $order->getCustomerName();
	
		$result['EP_Hash'] = md5(
			$result['EP_MerNo'] . 
			$config->getWebKey() . 
			$result['EP_OrderNo'] . 
			$result['EP_Sum']);

		return $result;
	}
}