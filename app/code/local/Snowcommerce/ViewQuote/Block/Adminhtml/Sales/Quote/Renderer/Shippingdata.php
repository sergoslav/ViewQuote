<?php

class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_Renderer_Shippingdata extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $quote)
	{
        /** @var $quote Mage_Sales_Model_Quote */
//        Mage::log($quote->getShippingAddress()->debug(), null, 'debug_SH.log');

        if($quote && ($address = $quote->getShippingAddress())){
            $html = $address->getData($this->getColumn()->getIndex());
        } else{
            $html = '';
        }
		return $this->_getEscapedValue($html);
	}

	protected function _getEscapedValue($value)
	{
		return addcslashes(htmlspecialchars($value),'\\\'');
	}
}

?>
