<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Element;

class Boolean extends \WePay\AdditionalCustomerAttributes\Block\Element\Select
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['value' => '0', 'label' => __('No')],
            ['value' => '1', 'label' => __('Yes')]
        ];
    }
}
