<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */

namespace WePay\AdditionalCustomerAttributes\Block;

use Magento\Backend\Block\Template\Context;

class Attributes extends \Magento\Framework\View\Element\Template
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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;
    
    /**
     * Data constructor.
     *
     * @param \WePay\AdditionalCustomerAttributes\Helper\Attributes $helper
     * @param \WePay\AdditionalCustomerAttributes\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session\Proxy $customerSession
     */
    public function __construct(
        Context $context,
        \WePay\AdditionalCustomerAttributes\Helper\Attributes $helper,
        \WePay\AdditionalCustomerAttributes\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        array $data = []
    ) {
        $this->helper       = $helper;
        $this->dataHelper   = $dataHelper;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    
    public function getAvailableAttributes($page = 1, $withCustomerValue = false, $customerId = 0)
    {
        $fields = [];
        if ($this->helper->getStatus()) {
            $fields = $this->helper->getAllAttributes($page, $withCustomerValue, $customerId);
        }
        return $fields;
    }
    
    public function getElement($attribute)
    {
        $layout = $this->getLayout();
        $template = 'WePay_AdditionalCustomerAttributes::form/elements/'.$attribute->getFrontendInput().'.phtml';
        /** @var \Magento\Framework\View\Element\Template $block */
        $class = ucwords($attribute->getFrontendInput());
        $block = $layout->createBlock("\WePay\AdditionalCustomerAttributes\Block\Element\\".$class)
            ->setName("element.".$attribute->getAttributeCode())
            ->setCurrentAttribute($attribute)
            ->setTemplate($template)->toHtml();
        return $block;
    }
    public function isHidden($attribute)
    {
        if (!$attribute->getIsSearchable()) {
            $value = $attribute->hasData('customer_value') ?
                $attribute->getCustomerValue():$attribute->getDefaultValue();
            if ($value !== 1) {
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
        return $this->getUrl('additionalcustomerattributes/customer/postData');
    }
}
