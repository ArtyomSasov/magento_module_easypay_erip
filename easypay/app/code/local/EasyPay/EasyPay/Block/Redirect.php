<?php
class EasyPay_EasyPay_Block_Redirect extends Mage_Core_Block_Template
{
	//Загружает шаблон (app\design\frontend\base\default\template\easypay\redirect.php)
	protected function _construct()
	{
		
		$this->setTemplate('easypay/redirect.php');
		parent::_construct();
	}


	//Возвращает форму с данными для easypay
	// @return Varien_Data_Form
	public function getForm()
	{		
		$paymentMethod = Mage::getModel('easypay/redirect');

		$form = new Varien_Data_Form();
		$form->setAction('https://ssl.easypay.by/weborder/?EP_Module=magento_1_7')
			->setId('easypay_redirect')
			->setName('easypay_redirect')
			->setMethod('POST')
			->setUseContainer(true);

		foreach ($paymentMethod->getRedirectFormFields() as $field => $value) {
			$form->addField($field, 'hidden', array('name' => $field, 'value'=>$value));
		}
		
		return $form;
	}
}