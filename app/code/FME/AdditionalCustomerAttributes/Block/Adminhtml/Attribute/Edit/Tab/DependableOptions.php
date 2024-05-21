<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Controller\RegistryConstants;

class DependableOptions extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \FME\AdditionalCustomerAttributes\Helper\Attributes
     */
    private $helper;
    /**
     * @var \FME\AdditionalCustomerAttributes\Helper\Data
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
     * @param \FME\AdditionalCustomerAttributes\Helper\Attributes $helper
     * @param \FME\AdditionalCustomerAttributes\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session\Proxy $customerSession
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \FME\AdditionalCustomerAttributes\Helper\Attributes $helper,
        \FME\AdditionalCustomerAttributes\Helper\Data $dataHelper,
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
