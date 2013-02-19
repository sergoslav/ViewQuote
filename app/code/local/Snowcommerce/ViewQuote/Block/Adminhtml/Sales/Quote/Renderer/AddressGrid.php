<?php

class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_Renderer_AddressGrid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    const DELIMETR_LINE = ', ';

	public function render(Varien_Object $quote)
	{
        /** @var $quote Mage_Sales_Model_Quote */
//        Mage::log($quote->getShippingAddress()->debug(), null, 'debug_SH.log');

        $html = '';
        if($quote && $address = $quote->getShippingAddress()){
//            $html = $address->getStreet(1)."\r\n".$address->getStreet(2)."\r\n".$address->getCity()."\r\n".$address->getRegion()."\r\n".$address->getPostcode()."\r\n".$address->getCountry();
            if ($address->getCompany()) {
                $html .= $address->getCompany().self::DELIMETR_LINE;
            }
            if ($address->getStreet(1)) {
                $html .= $address->getStreet(1).self::DELIMETR_LINE;
            }
            if ($address->getStreet(2)) {
                $html .= $address->getStreet(2).self::DELIMETR_LINE;
            }
            $html .= $address->getCity().self::DELIMETR_LINE;
            $html .= $address->getRegion().self::DELIMETR_LINE;
            $html .= $address->getPostcode().self::DELIMETR_LINE;

            if ($address->getCountry() && $country = Mage::getModel('directory/country')->loadByCode($address->getCountry())){
                $html .= $country->getName();;
            } else {
                $html .= $address->getCountry();

            }
        }

        return $this->_getEscapedValue($html);
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value),'\\\'');
    }
}