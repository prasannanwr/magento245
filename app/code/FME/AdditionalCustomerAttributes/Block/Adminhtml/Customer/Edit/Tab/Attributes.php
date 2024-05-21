<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */

namespace FME\AdditionalCustomerAttributes\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Controller\RegistryConstants;

class Attributes extends \Magento\Backend\Block\Template
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
        Context $context,
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
    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
    /**
     * @return string|null
     */
    public function getCustomerData()
    {
        return $this->_backendSession->getCustomerData();
    }
    
    public function getAvailableAttributes($page = 1, $withCustomerValue = true, $customerId = 0)
    {
        
        $data = $this->getCustomerData();
        $groupId = isset($data['account']['group_id'])?$data['account']['group_id']:0;
        $fields = [];
        $customerId = $this->getCustomerId();
        if ($this->helper->getStatus()) {
            $fields = $this->helper->getAllAttributesForAdmin($withCustomerValue, $customerId, 0 , $groupId);
        }
        return $fields;
    }
    
    public function getElement($attribute)
    {
        $layout = $this->getLayout();
        $template = 'FME_AdditionalCustomerAttributes::elements/'.$attribute->getFrontendInput().'.phtml';
        /** @var \Magento\Framework\View\Element\Template $block */
        $class = ucwords($attribute->getFrontendInput());
        $block = $layout->createBlock("\FME\AdditionalCustomerAttributes\Block\Element\\".$class)
            ->setName("element.".$attribute->getAttributeCode())
            ->setCurrentAttribute($attribute)
            ->setTemplate($template)->toHtml();
        return $block;
    }
    public function isHidden($attribute)
    {
        if ($attribute->getIsSearchable()) {
            $value = $attribute->hasData('customer_value') ?
                $attribute->getCustomerValue():$attribute->getDefaultValue();
            if ($value !== '') {
                return true;
            }
        }
        return false;
    }
    /**
     * Get form action URL for POST booking request
     *
     * @return string
     */
    public function getPostUrl()
    {
        return $this->getUrl('additionalcustomerattributes/postData');
    }
}
