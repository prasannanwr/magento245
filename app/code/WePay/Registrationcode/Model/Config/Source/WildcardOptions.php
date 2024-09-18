<?php
namespace WePay\Registrationcode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class WildcardOptions implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Equals')],
            ['value' => '1', 'label' => __('Contains')],
            ['value' => '2', 'label' => __('Does not contain')],
            ['value' => '3', 'label' => __('Ends with')],
            ['value' => '4', 'label' => __('Does not ends with')],
            ['value' => '5', 'label' => __('Begin with')],
            ['value' => '6', 'label' => __('Does not begin with')]
        ];
    }
}
