<?php

namespace WePay\Inventory\Controller\Adminhtml\RunNow;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use WePay\Inventory\Helper\Data as helperData;

class Index extends Action
{
    const ADMIN_RESOURCE = 'WePay_Inventory::ajax_action';
    const PATH_SCRIPT = 'wepay_inventory/path_files/files';


    protected $resultPageFactory;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        helperData $helperData
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        try {
            $files = $this->scopeConfig->getValue(self::PATH_SCRIPT);
            if (file_exists($files)) {
                ob_start();
                include $files;
                $output = ob_get_clean();
            }
            
            $this->helperData->flushCache();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $message = $objectManager->create('Magento\Framework\Message\ManagerInterface');
            $message->addSuccessMessage(__('Running Manual Successfully!.'));
            $result->setData(['status' => 'success']);
        } catch (\Exception $e) {
            $result->setData(['status' => 'error', 'message' => $e->getMessage()]);
        }
        return $result;
    }
}