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
 * Adminhtml sales orders grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Snowcommerce_ViewQuote_Block_Adminhtml_Sales_Quote_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_quote_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'viewQuote/sales_quote_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());

        $collection
            ->filterByNotOrdered()
            ->addAddressFields()
            ->filterByEmailIsset()
//            ->addOrderData()
        ;

//        Zend_Debug::dump($collection->getFirstItem()->getData());die();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('real_quote_id', array(
            'header'=> Mage::helper('sales')->__('Quote #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'entity_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Created From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Created On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Firstname'),
            'index' => 'shipping_firstname',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Lastname'),
            'index' => 'shipping_lastname',
        ));

        $this->addColumn('email_address', array(
            'header' => Mage::helper('sales')->__('Email Address'),
            'index' => 'shipping_email',
        ));

//        $this->addColumn('status', array(
//            'header' => Mage::helper('sales')->__('Status'),
//            'index' => 'status',
//            'type'  => 'options',
//            'width' => '70px',
//            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
//        ));

//        $this->addColumn('order_increment_id', array(
//            'header' => Mage::helper('sales')->__('Order ID'),
//            'index' => 'order_increment_id',
//            'type'  => 'text',
//            'width' => '70px',
//        ));
//
//        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
//            $this->addColumn('action',
//                array(
//                    'header'    => Mage::helper('sales')->__('Action'),
//                    'width'     => '50px',
//                    'type'      => 'action',
//                    'getter'     => 'getId',
//                    'actions'   => array(
//                        array(
//                            'caption' => Mage::helper('sales')->__('View'),
//                            'url'     => array('base'=>'*/sales_order/view'),
//                            'field'   => 'order_id'
//                        )
//                    ),
//                    'filter'    => false,
//                    'sortable'  => false,
//                    'index'     => 'stores',
//                    'is_system' => true,
//            ));
//        }
//        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));
//
//        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
//        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

//        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
//            $this->getMassactionBlock()->addItem('hold_order', array(
//                 'label'=> Mage::helper('sales')->__('Hold'),
//                 'url'  => $this->getUrl('*/sales_order/massHold'),
//            ));
//        }

//        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
//            $this->getMassactionBlock()->addItem('unhold_order', array(
//                 'label'=> Mage::helper('sales')->__('Unhold'),
//                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
//            ));
//        }

//        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
//             'label'=> Mage::helper('sales')->__('Print Invoices'),
//             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
//        ));
//
//        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
//             'label'=> Mage::helper('sales')->__('Print Packingslips'),
//             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
//        ));
//
//        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
//             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
//             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
//        ));
//
//        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
//             'label'=> Mage::helper('sales')->__('Print All'),
//             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
//        ));
//
//        $this->getMassactionBlock()->addItem('print_shipping_label', array(
//             'label'=> Mage::helper('sales')->__('Print Shipping Labels'),
//             'url'  => $this->getUrl('*/sales_order_shipment/massPrintShippingLabel'),
//        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/adminhtml_quote/view', array('quote_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}
