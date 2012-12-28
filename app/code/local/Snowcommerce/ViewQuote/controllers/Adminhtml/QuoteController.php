<?php

/**
 * Wms XmlFormat admin controller
 *
 * @category    Oggetto
 * @package     Oggetto_Wms
 * @author
 */
class Snowcommerce_ViewQuote_Adminhtml_QuoteController extends Mage_Adminhtml_Controller_Action {

    /**
     * Init controller actions
     *
     * @return Snowcommerce_ViewQuote_Adminhtml_QuoteController
     */
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('sales/view_quote')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Orders not completed'), $this->__('Orders not completed'));

        return $this;
    }

    /**
     * Initialize order model instance
     *
     * @return Mage_Sales_Model_Order || false
     */
    protected function _initQuote()
    {
        $id = $this->getRequest()->getParam('quote_id');
        $quote = Mage::getModel('viewQuote/sales_quote')->loadByIdWithoutStore($id);

//        Zend_Debug::dump($quote->debug());die();

        if (!$quote->getId()) {
            $this->_getSession()->addError($this->__('This quote no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('sales_quote', $quote);
        Mage::register('current_quote', $quote);
        return $quote;
    }

    /**
     * Quotes grid
     *
     */
    public function indexAction() {
        $this->_title($this->__('viewQuote'))->_title($this->__('Orders not completed'));

        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Quote grid
     */
    public function gridAction() {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * View quote detale
     */
    public function viewAction()
    {
//        die(__METHOD__);
        $this->_title($this->__('Sales'))->_title($this->__('Quotes'));

        if ($quote = $this->_initQuote()) {
            $this->_initAction();

            $this->_title(sprintf("#%s", $quote->getRealOrderId()));

            $this->renderLayout();
        }
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        #TODO: Do it!
        $fileName   = 'orders.csv';
        $grid       = $this->getLayout()->createBlock('adminhtml/sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        #TODO: Do it!
        $fileName   = 'orders.xml';
        $grid       = $this->getLayout()->createBlock('adminhtml/sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    /**
     * Order transactions grid ajax action
     *
     */
    public function transactionsAction()
    {
        die(__METHOD__);
        $this->_initOrder();
        $this->loadLayout(false);
        $this->renderLayout();
    }


}