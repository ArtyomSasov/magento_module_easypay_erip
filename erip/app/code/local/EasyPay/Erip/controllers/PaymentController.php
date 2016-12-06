<?php
class EasyPay_Erip_PaymentController extends Mage_Core_Controller_Front_Action
{

	//Перенаправление после подтверждения заказа в корзине
	public function redirectAction() 
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setEripQuoteId($session->getQuoteId());
		$this->getResponse()->setBody($this->getLayout()->createBlock('erip/redirect')->toHtml());
		$session->unsQuoteId();
		$session->unsRedirectUrl();
	}

	//Отмена платежа клиентом 
	public function failAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setQuoteId($session->getEripQuoteId(true));
		$session->getQuote()->setIsActive(true)->save();
		$session->addError(Mage::helper('erip')->__('Оплата отменена. Пожалуйста, повторите попытку позже.'));
		$this->_redirect('checkout/cart');
	}

	//Клиент вернулся после оплаты
	public function returnAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setQuoteId($session->getEripQuoteId(true));
		Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $get_params = Mage::app()->getRequest()->getQuery();
        $session->addNotice( Mage::helper('erip')->__('Счет добавлен в систему ЕРИП для оплаты. Номер заказа для оплаты: '.$get_params['EP_OrderNo']));
        $this->_redirect('checkout/onepage/success', array('_secure' => true));
	}

	//Обработка уведомлений
  	public function notifyAction()
	{
        $post_params = Mage::app()->getRequest()->getPost();
		$hush = Mage::getModel('erip/redirect')->validateRequest($post_params);
		if ($hush === 'error') {
			header("HTTP/1.0 400 Bad Request");
			print $status = 'FAILED | incorrect digital signature';
		} elseif ($hush) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($post_params['order_mer_code']);
            $isCustomerNotified = false;
            $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_PROCESSING,'Оплачено Erip',$isCustomerNotified);
            $order->setData('state', Mage_Sales_Model_Order::STATE_PROCESSING);
            $order->save();
            header("HTTP/1.0 200 OK");
            print $status = 'OK | the notice is processed';
		} else {
			header("HTTP/1.0 400 Bad Request");
			print $status = 'FAILED | the notice is not processed';	}
	}
}
