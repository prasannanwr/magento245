<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit;

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
