<?php

class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_View_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getQuote()
    {
        return Mage::registry('sales_quote');
    }

  protected function _prepareForm()
  {
      $quote = $this->getQuote();
      $shippingAddressData = $quote->getShippingAddress()->getData();

      if (!isset($shippingAddressData['email']) || !$shippingAddressData['email']) {
          return parent::_prepareForm();
      }


      $form = new Varien_Data_Form();
      $this->setForm($form);

      $fieldsetQuote = $form->addFieldset('quote_information_', array('legend'=>'Quote #'.$quote->getId(),));

      $fieldsetQuote->addField('date', 'note', array(
                                                      'label'     => Mage::helper('viewQuote')->__('Date of Initialization Order'),
                                                      'text'     => '<b>'.$this->formatDate($quote->getCreatedAt(), 'medium', true).'</b>',
                                                      'name'      => 'date',
                                                 ));

      $_store = Mage::getModel('core/store')->load($quote->getStoreId());
      $fieldsetQuote->addField('website', 'note', array(
                                                       'label'     => Mage::helper('viewQuote')->__('Store Name'),
                                                       'text'     => "<b>{$_store->getName()}</b>",
                                                       'name'      => 'title',
                                                  ));

      $fieldsetAccount = $form->addFieldset('quote_account_information', array('legend'=>$this->__('Customer Information'),));

      $fieldsetAccount->addField('name', 'note', array(
          'label'     => Mage::helper('viewQuote')->__('Customer Name'),
          'text'     => '<b>'.$shippingAddressData['firstname'].' '.$shippingAddressData['lastname'].'</b>',
          'name'      => 'title',
      ));
      $fieldsetAccount->addField('email', 'note', array(
          'label'     => Mage::helper('viewQuote')->__('Email Address'),
          'text'     => "<a href=\"mailto:{$shippingAddressData['email']}\">
                            <strong>{$shippingAddressData['email']}</strong>
                        </a>",
          'name'      => 'title',
      ));

      $fieldsetAddress = $form->addFieldset('quote_address_information', array('legend'=>$this->__('Shipping Address'),));

      $fieldsetAddress->addField('address', 'note', array(
                                                      'label'     => Mage::helper('viewQuote')->__('Shipping Address'),
                                                      'text'     => $quote->getShippingAddress()->getFormated(true),
                                                      'name'      => 'title',
                                                 ));


//
//      if ( Mage::getSingleton('adminhtml/session')->getWmsData() )
//      {
//          $form->setValues(Mage::getSingleton('adminhtml/session')->getWmsData());
//          Mage::getSingleton('adminhtml/session')->setWmsData(null);
//      } elseif ( Mage::registry('wms_data') ) {
//          $form->setValues(Mage::registry('wms_data')->getData());
//      }
      return parent::_prepareForm();
  }
}