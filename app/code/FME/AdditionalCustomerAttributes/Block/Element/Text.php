<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Element;

class Text extends \FME\AdditionalCustomerAttributes\Block\Element\AbstractRenderer
{
    /**
     * Return filtered and escaped value
     *
     * @return string
     */
    public function getEscapedValue()
    {
        return $this->escapeHtml($this->_applyOutputFilter($this->getValue()));
    }

    /**
     * Return array of validate classes
     *
     * @param boolean $withRequired
     * @return array
     */
    protected function _getValidateClasses($withRequired = true)
    {
        $classes = parent::_getValidateClasses($withRequired);
        $rules = $this->getCurrentAttribute()->getValidateRules();
        if (!empty($rules['min_text_length'])) {
            $classes[] = 'validate-length';
            $classes[] = 'minimum-length-' . $rules['min_text_length'];
        }
        if (!empty($rules['max_text_length'])) {
            if (!in_array('validate-length', $classes)) {
                $classes[] = 'validate-length';
            }
            $classes[] = 'maximum-length-' . $rules['max_text_length'];
        }
        return $classes;
    }
}
