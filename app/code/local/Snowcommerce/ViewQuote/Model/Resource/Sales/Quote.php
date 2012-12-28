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
 * Quote resource model
 *
 * @category    Mage
 * @package     Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Snowcommerce_ViewQuote_Model_Resource_Sales_Quote extends Mage_Sales_Model_Resource_Quote
{

//    /**
//     * Init virtual grid records for entity
//     *
//     * @return Mage_Sales_Model_Resource_Order
//     */
//    protected function _initVirtualGridColumns()
//    {
//        parent::_initVirtualGridColumns();
//        $adapter       = $this->getReadConnection();
//        $ifnullFirst   = $adapter->getIfNullSql('{{table}}.firstname', $adapter->quote(''));
//        $ifnullLast    = $adapter->getIfNullSql('{{table}}.lastname', $adapter->quote(''));
//        $concatAddress = $adapter->getConcatSql(array($ifnullFirst, $adapter->quote(' '), $ifnullLast));
//        $this->addVirtualGridColumn(
//            'billing_name',
//            'sales/order_address',
//            array('billing_address_id' => 'entity_id'),
//            $concatAddress
//        )
//            ->addVirtualGridColumn(
//            'shipping_name',
//            'sales/order_address',
//            array('shipping_address_id' => 'entity_id'),
//            $concatAddress
//        );
//
//        return $this;
//    }
}

