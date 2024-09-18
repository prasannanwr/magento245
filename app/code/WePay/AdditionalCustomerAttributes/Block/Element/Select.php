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

class Select extends \WePay\AdditionalCustomerAttributes\Block\Element\AbstractRenderer
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        $empty = $this->isRequired();
        return $this->getCurrentAttribute()->getSource()->getAllOptions(!$empty);
    }
}
