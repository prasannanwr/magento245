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

use function PHPUnit\Framework\isNull;

class Checkbox extends \WePay\AdditionalCustomerAttributes\Block\Element\Select
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->getCurrentAttribute()->getSource()->getAllOptions(false);
    }

    /**
     * Return array of values
     *
     * @return array
     */
    public function getValues()
    {
        $value = $this->getValue();
        if(is_array($value)) {
            return explode(',', $value);
        } else {
            return false;
        }
    }

    /**
     * Check is value selected
     *
     * @param string $value
     * @return boolean
     */
    public function isSelected($value)
    {
        if(is_array($this->getValues())) {
            return in_array($value, $this->getValues());
        } else {
            return false;
        }
    }
}
