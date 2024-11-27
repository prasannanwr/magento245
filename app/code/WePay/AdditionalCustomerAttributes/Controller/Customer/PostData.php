<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Controller\Customer;

use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\ForwardFactory;

class PostData extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \WePay\GDPR\Helper\Data
     */
    private $helper;

    /**
     * @var \WePay\AdditionalCustomerAttributes\Model\AttributeFactory
     */
    private $attributeFactory;

    /**
     * @var PageFactory
     */
    private $urlInterface;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \WePay\AdditionalCustomerAttributes\Helper\Data $helper,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \WePay\AdditionalCustomerAttributes\Model\CustomerValues $attributeFactory
    ) {

        $this->helper           = $helper;
        $this->customerSession  = $customerSession;
        $this->attributeFactory = $attributeFactory;
        $this->urlInterface     = $context->getUrl();
        parent::__construct($context);
    }//end __construct()

    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->helper->getStatus()) {
            throw new NotFoundException(__('Page disabled found.'));
        }
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->customerSession->authenticate();
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            $this->customerSession->setBeforeAuthUrl($this->urlInterface->getCurrentUrl());
        }
        return parent::dispatch($request);
    }
    /**
     * Customer order history
     *
     * @return \Magento\Framework\View\Result\Page $resultPage
     */
    public function execute()
    {
        try {
            $post = (array) $this->getRequest()->getPost();
            if (empty($post)) {
                $this->messageManager->addError(__('Changes were failed to update, please try again.'));
                $this->_redirect('additionalcustomerattributes/customer/attributes');
                return;
            }
            $customer = $this->customerSession->getCustomer();
            $this->attributeFactory->saveCustomerValues($post, $customer->getId());
            $this->messageManager->addSuccess(__('Your changes were successfully updated.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('additionalcustomerattributes/customer/attributes');
        return;
    }//end execute()
}//end class
