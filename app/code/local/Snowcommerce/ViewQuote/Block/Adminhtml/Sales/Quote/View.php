<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales order view
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_View extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId    = 'quote_id';
        $this->_controller  = 'adminhtml_quote';
        $this->_mode        = 'view';

        parent::__construct();

        $this->setId('sales_quote_view');
        $order = $this->getQuote();


//        if ($this->_isAllowedAction('emails') && !$order->isCanceled()) {
//            $message = Mage::helper('sales')->__('Are you sure you want to send order email to customer?');
//            $this->addButton('send_notification', array(
//                'label'     => Mage::helper('sales')->__('Send Email'),
//                'onclick'   => "confirmSetLocation('{$message}', '{$this->getEmailUrl()}')",
//            ));
//        }
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('save');
    }

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getQuote()
    {
        return Mage::registry('sales_quote');
    }

    /**
     * Retrieve Order Identifier
     *
     * @return int
     */
    public function getQuoteId()
    {
        return $this->getQuote()->getId();
    }

    public function getHeaderText()
    {
        if ($_extOrderId = $this->getQuote()->getExtOrderId()) {
            $_extOrderId = '[' . $_extOrderId . '] ';
        } else {
            $_extOrderId = '';
        }
        return Mage::helper('sales')->__('Quote # %s %s | %s', $this->getQuote()->getId(), $_extOrderId, $this->formatDate($this->getQuote()->getCreatedAt(), 'medium', true));
    }

    public function getUrl($params='', $params2=array())
    {
        $params2['quote_id'] = $this->getQuoteId();
        return parent::getUrl($params, $params2);
    }

    public function getEmailUrl()
    {
        return $this->getUrl('*/*/email');
    }


    public function getCommentUrl()
    {
        return $this->getUrl('*/*/comment');
    }

    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/' . $action);
    }

    /**
     * Return back url for view grid
     *
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->getQuote()->getBackUrl()) {
            return $this->getQuote()->getBackUrl();
        }

        return $this->getUrl('*/*/');
    }
}
