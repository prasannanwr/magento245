<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Save checkout fields with quote shipping address.
 *
 */
class CustomerSaveAfter implements ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /**
     * @var \WePay\AdditionalCustomerAttributes\Helper\Data
     */
    private $helper;

    /**
     * @var \WePay\AdditionalCustomerAttributes\Model\AttributeFactory
     */
    private $attributeFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    private $messageManager;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \WePay\AdditionalCustomerAttributes\Helper\Data $helper,
        \WePay\AdditionalCustomerAttributes\Model\CustomerValues $attributeFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        $this->attributeFactory = $attributeFactory;
        $this->helper          = $helper;
        $this->messageManager = $messageManager;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getCustomer();
        $post = $this->request->getPost();
        try {
            $this->attributeFactory->saveCustomerValues($post, $customer->getId());
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        return $this;
    }
}
