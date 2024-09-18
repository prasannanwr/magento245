<?php
namespace WePay\Inventory\Model\Config;

/**
 * Used in creating options for getting product type value
 *
 */
class Checkbox
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => '1', 'label'=>__('Checkbox')]];
    }
}