<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
        
    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \Magento\Checkout\Model\Session\Proxy $checkoutSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone
    ) {
        $this->scopeConfig     = $context->getScopeConfig();
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->timezone = $timezone;
        $this->date     = $date;
        parent::__construct($context);
    }
    
    /**
     * @param $config
     * @return mixed
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue(
            $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }//end getConfig()
    
    /**
     * @return array
     */
    public function setCoafFields($data)
    {
        $exitingValues = [];
        if ($this->checkoutSession->getCoaf() != "") {
            $exitingValues = $this->checkoutSession->getCoaf();
        }
        $this->arrayMergeRecursiveValues($data, $exitingValues);
        $this->checkoutSession->setCoaf($exitingValues);
    }//end setCoafFields()
    
    /**
     * @return void
     */
    public function arrayMergeRecursiveValues($user, &$default)
    {
        foreach ($user as $key => $value) {
            if (is_array($value)) {
                $this->arrayMergeRecursiveValues($user[$key], $default[$key]);
            } else {
                $default[$key] = $value;
            }
        }
    }
    /**
     * @return void
     */
    public function setCoafFieldsMainDetails($data)
    {
        $exitingValues = [];
        if ($this->checkoutSession->getCoafMainDetails() != "") {
            $exitingValues = $this->checkoutSession->getCoafMainDetails();
        }
        $this->arrayMergeRecursiveValues($data, $exitingValues);
        $this->checkoutSession->setCoafMainDetails($exitingValues);
    }//end setCoafFields()
    /**
     * @return boolean
     */
    public function showBreadcrumbs()
    {
        return $this->getConfig('additionalcustomerattributes/general/breadcrumbs');
    }
    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->getConfig('additionalcustomerattributes/general/active');
    }//end getConfig()
    
    /**
     * @return boolean
     */
    public function getHeading()
    {
        return $this->getConfig('additionalcustomerattributes/general/heading');
    }//end getConfig()
}
