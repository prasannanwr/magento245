<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit;

class Tabs extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tabs
{
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTabAfter(
            'depends',
            [
                'label' => __('Dependable Properties'),
                'title' => __('Guarding Attributes can only be the ones are select, multiselect, radio, checkbox'),
                'content' => $this->getChildHtml('depends')
            ],
            'front'
        );

        return parent::_beforeToHtml();
    }
}
