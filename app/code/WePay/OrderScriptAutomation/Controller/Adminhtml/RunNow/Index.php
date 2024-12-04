<?php

namespace WePay\OrderScriptAutomation\Controller\Adminhtml\RunNow;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Sales\Model\Order;
use WePay\OrderScriptAutomation\Helper\Data as helperData;
use WePay\OrderScriptAutomation\Model\ResourceModel\CustomerGroupExtension;

class Index extends Action
{
    const ADMIN_RESOURCE = 'WePay_OrderScriptAutomation::ajax_action';
    const PATH_SCRIPT = 'wepay_orderscriptautomation/path_files/files';


    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $order;
    protected $customerGroupExtension;

    public function __construct(
        Context $context,
        Order $order,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        helperData $helperData,
        CustomerGroupExtension $customerGroupExtension
    ) {
        $this->order = $order;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->helperData = $helperData;
        $this->customerGroupExtension = $customerGroupExtension;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $orderId = $this->getRequest()->getParam('order_id');
        try {
            $files = $this->scopeConfig->getValue(self::PATH_SCRIPT);
//            $files = '/var/wegift_dev_module_test.php';
//            $files = BP . $files;
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/WePay-status.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);

            $order = $this->order->load($orderId);
            //$orderId = $order->getId();
            $customerGroup = $order->getCustomerGroupId();
            $runMode = $this->customerGroupExtension->getCustomScriptMode($customerGroup);

            if (file_exists($files)) {
                ob_start();
                include $files;
                $output = ob_get_clean();
            } else {
                $logger->info('Order script file doesnt exist');
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
