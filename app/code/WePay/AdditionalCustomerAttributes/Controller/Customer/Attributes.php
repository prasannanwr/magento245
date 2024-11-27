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

class Attributes extends \Magento\Framework\App\Action\Action
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
     * @var PageFactory
     */
    private $resultPageFactory;

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
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->urlInterface      = $context->getUrl();
        $this->customerSession   = $customerSession;
        $this->helper            = $helper;
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
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($this->helper->getHeading());
        $resultPage->getConfig()->setDescription($this->helper->getHeading());
        $resultPage->getConfig()->setKeywords($this->helper->getHeading());
        if ($this->helper->showBreadcrumbs()) {
            /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbsBlock */
            $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbsBlock) {
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'link'  => $this->_url->getUrl('')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'additionalcustomerattributes',
                    [
                        'label' => $this->helper->getHeading(),
                    ]
                );
            }
        }
        return $resultPage;
    }//end execute()
}//end class
