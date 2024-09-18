<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues;

/**
 * WePay Additional Customer Attributes EAV additional attribute resource collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Resource model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'WePay\AdditionalCustomerAttributes\Model\CustomerValues',
            'WePay\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues'
        );
    }
}
