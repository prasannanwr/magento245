<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues;

/**
 * FME Additional Customer Attributes EAV additional attribute resource collection
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
            'FME\AdditionalCustomerAttributes\Model\CustomerValues',
            'FME\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues'
        );
    }
}
