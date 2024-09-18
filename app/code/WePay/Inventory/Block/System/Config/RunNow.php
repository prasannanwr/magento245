<?php
namespace WePay\Inventory\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class RunNow extends Field
{
    protected $_template = 'WePay_Inventory::system/config/button/runnow.phtml';

    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
 
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
    public function getCustomUrl()
    {
        return $this->getUrl('wepay_inventory/runnow/index');
    }
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'id' => 'btn_id', 
            'label' => __('Run Now!'), 
        ]);
        return $button->toHtml();
    }
}