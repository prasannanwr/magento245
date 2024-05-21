<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Observer;

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
class CustomerSaveBefore implements ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /**
     * @var \FME\AdditionalCustomerAttributes\Helper\Data
     */
    private $helper;

    /**
     * @var \FME\AdditionalCustomerAttributes\Model\AttributeFactory
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
        \FME\AdditionalCustomerAttributes\Helper\Data $helper,
        \FME\AdditionalCustomerAttributes\Model\CustomerValues $attributeFactory,
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
        $post = $this->request->getPost();
        try {
            $this->attributeFactory->validateCustomerValues($post);
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
        return $this;
    }
}
