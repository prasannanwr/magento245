<?php
namespace Prasanna\Invitecode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Yes')],
            ['value' => '2', 'label' => __('No')],
        ];
    }
}
