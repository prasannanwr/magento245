<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Controller\Adminhtml\Registrationcode;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        //$resultPage->setActiveMenu('WePay_Registrationcode::registrationcode');
        $resultPage->getConfig()->getTitle()->prepend(__("Manage Codes"));
        return $resultPage;
    }
}
