<?php

class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_View_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('quote_tabs');
      $this->setDestElementId('sale_quote_view_form');
      $this->setTitle($this->__('Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => $this->__('Information'),
          'title'     => $this->__('Information'),
          'content'   => $this->getLayout()->createBlock('viewQuote/adminhtml_sales_quote_view_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}