<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml;

/**
 * Adminhtml catalog product attributes block
 *
 */
class Attribute extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_attribute';
        $this->_blockGroup = 'WePay_AdditionalCustomerAttributes';
        $this->_headerText = __('Additional Customer Attributes Fields');
        $this->_addButtonLabel = __('Add New Attribute');
        parent::_construct();
    }
}
