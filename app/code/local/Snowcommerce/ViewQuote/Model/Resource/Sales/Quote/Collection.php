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
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Quotes collection
 *
 * @category    Mage
 * @package     Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Snowcommerce_ViewQuote_Model_Resource_Sales_Quote_Collection extends Mage_Sales_Model_Resource_Quote_Collection
{

    /**
     * Resource initialization
     *
     */
    protected function _construct()
    {
        $this->_init('viewQuote/sales_quote');
    }

//    /**
//     * Redefine default filters
//     *
//     * @param string $field
//     * @param mixed $condition
//     * @return Varien_Data_Collection_Db
//     */
//    public function addFieldToFilter($field, $condition = null)
//    {
//        if ($field == 'stores') {
//            return $this->addStoreFilter($condition);
//        } else {
//            return parent::addFieldToFilter($field, $condition);
//        }
//    }
//
//    /**
//     * Add Stores Filter
//     *
//     * @param mixed $storeId
//     * @param bool  $withAdmin
//     * @return Mage_Poll_Model_Resource_Poll_Collection
//     */
//    public function addStoreFilter($storeId, $withAdmin = true)
//    {
//        $this->getSelect()->join(
//            array('store_table' => $this->getTable('cmscheckout/steps_store')),
//            'main_table.id = store_table.step_id',
//            array()
//        )
//            ->where('store_table.store_id in (?)', ($withAdmin ? array(0, $storeId) : $storeId))
//            ->group('main_table.id');
//
//        /*
//         * Allow analytic functions usage
//         */
//        $this->_useAnalyticFunction = true;
//
//        return $this;
//    }

    /**
     * Add orders data
     *
     * @return Snowcommerce_ViewQuote_Model_Resource_Sales_Quote_Collection
     */
    public function addOrderData()
    {
        $quoteIds = $this->getColumnValues('entity_id');
        $ordersToQuote = array();

        if (count($quoteIds) > 0) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('sales/order'))
                ->where('quote_id IN(?)', $quoteIds);
            $result = $this->getConnection()->fetchAll($select);

            foreach ($result as $row) {
                if (!isset($ordersToQuote[$row['quote_id']])) {
                    $ordersToQuote[$row['quote_id']] = array();
                }
                $ordersToQuote[$row['quote_id']]['order_increment_id'] = $row['increment_id'];
            }
        }

        foreach ($this as $item) {
            $_itemId = $item->getId();
            if (isset($ordersToQuote[$_itemId])) {
                $item->addData($ordersToQuote[$_itemId]);
            } else {
                $item->addData(array(
                    'order_increment_id'    => false,
                ));
            }
        }

        return $this;
    }

    /**
     * Get only not ordered quotes
     *
     * @return Snowcommerce_ViewQuote_Model_Resource_Sales_Quote_Collection
     */
    public function filterByNotOrdered() {
         $this->getSelect()
            ->where('main_table.entity_id not in (select quote_id from '.$this->getTable('sales/order').')');

        /*
         * Allow analytic functions usage
         */
        $this->_useAnalyticFunction = true;

        return $this;
    }

    /**
     * Get only not ordered quotes
     *
     * @return Snowcommerce_ViewQuote_Model_Resource_Sales_Quote_Collection
     */
    public function filterByEmailIsset() {
         $this->getSelect()
            ->where('main_table.entity_id in (select quote_id from '.$this->getTable('sales/quote_address').' where email is not NULL)')
//            ->where('main_table.entity_id not in (select quote_id from '.$this->getTable('sales/quote_address').' where email is NULL)')
         ;

        /*
         * Allow analytic functions usage
         */
        $this->_useAnalyticFunction = true;

        return $this;
    }


    /**
     * Add addresses information to select
     *
     * @return Mage_Sales_Model_Resource_Collection_Abstract
     */
    public function addAddressFields()
    {
        return $this->_addAddressFields();
    }

    /**
    * Join table sales_flat_order_address to select for billing and shipping order addresses.
    * Create corillation map
    *
    * @return Mage_Sales_Model_Resource_Order_Collection
    */
    protected function _addAddressFields()
    {
        $billingAliasName = 'billing_o_a';
        $shippingAliasName = 'shipping_o_a';
        $joinTable = $this->getTable('sales/quote_address');

        $this
            ->addFilterToMap('billing_firstname', $billingAliasName . '.firstname')
            ->addFilterToMap('billing_lastname', $billingAliasName . '.lastname')
            ->addFilterToMap('billing_telephone', $billingAliasName . '.telephone')
            ->addFilterToMap('billing_postcode', $billingAliasName . '.postcode')

            ->addFilterToMap('shipping_firstname', $shippingAliasName . '.firstname')
            ->addFilterToMap('shipping_lastname', $shippingAliasName . '.lastname')
            ->addFilterToMap('shipping_telephone', $shippingAliasName . '.telephone')
            ->addFilterToMap('shipping_email', $shippingAliasName . '.email')
        ;

        $this
            ->getSelect()

            ->joinLeft(
            array($billingAliasName => $joinTable),
            "(main_table.entity_id = {$billingAliasName}.quote_id"
                . " AND {$billingAliasName}.address_type = 'billing')",
            array(
                 'billing_firstname' => $billingAliasName . '.firstname',
                 'billing_lastname' => $billingAliasName . '.lastname',
                 'billing_telephone' => $billingAliasName . '.telephone',
                 'billing_email' => $billingAliasName . '.email',
            )
        )
            ->joinLeft(
            array($shippingAliasName => $joinTable),
            "(main_table.entity_id = {$shippingAliasName}.quote_id"
            . " AND {$shippingAliasName}.address_type = 'shipping')",
            array(
                 'shipping_firstname' => $shippingAliasName . '.firstname',
                 'shipping_lastname' => $shippingAliasName . '.lastname',
                 'shipping_telephone' => $shippingAliasName . '.telephone',
                 'shipping_email' => $shippingAliasName . '.email',
            )
        );

        Mage::getResourceHelper('core')->prepareColumnsList($this->getSelect());
        return $this;
    }

}

