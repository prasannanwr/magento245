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

class Boolean extends \FME\AdditionalCustomerAttributes\Block\Element\Select
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
