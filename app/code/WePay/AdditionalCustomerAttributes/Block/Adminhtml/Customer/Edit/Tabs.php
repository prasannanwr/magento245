<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml\Customer\Edit;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
/**
 * Customer account form block
 */
class Tabs extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \WePay\AdditionalCustomerAttributes\Helper\Attributes $helper,
        \WePay\AdditionalCustomerAttributes\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->helper       = $helper;
        $this->dataHelper   = $dataHelper;
        $this->_coreRegistry= $registry;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
 
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return $this->dataHelper->getHeading();
    }
 
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->dataHelper->getHeading();
    }
 
    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
 
    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }
 
    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }
 
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    public function getAvailableAttributes($page = 1, $withCustomerValue = false, $customerId = 0)
    {
        $page = 1;
        $withCustomerValue = true;
        $fields = [];
        $customerId = $customerId == 0?$this->getCustomerId():$customerId;
        if ($this->dataHelper->getStatus()) {
            $fields = $this->helper->getAllAttributes($page, $withCustomerValue, $customerId);
        }
        return $fields;
    }

    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('wepay_aca_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => $this->dataHelper->getHeading()]);
        $this->setForm($form);
        return $this;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
    /**
     * Prepare the layout.
     *
     * @return $this
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            'WePay\AdditionalCustomerAttributes\Block\Adminhtml\Customer\Edit\Tab\Attributes'
        )->setTemplate('WePay_AdditionalCustomerAttributes::accountAttributes.phtml')->toHtml();
        return $html;
    }
}
