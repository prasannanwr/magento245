<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Controller\RegistryConstants;

class DependableOptions extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \WePay\AdditionalCustomerAttributes\Helper\Attributes
     */
    private $helper;
    /**
     * @var \WePay\AdditionalCustomerAttributes\Helper\Data
     */
    private $dataHelper;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
	/**
     * Data constructor.
     *
     * @param \WePay\AdditionalCustomerAttributes\Helper\Attributes $helper
     * @param \WePay\AdditionalCustomerAttributes\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session\Proxy $customerSession
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \WePay\AdditionalCustomerAttributes\Helper\Attributes $helper,
        \WePay\AdditionalCustomerAttributes\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->helper       = $helper;
        $this->dataHelper   = $dataHelper;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('additionalcustomerattributes/attribute/options');
    }
}
