<?php
namespace WePay\OrderScriptAutomation\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order;
use WePay\OrderScriptAutomation\Helper\Data;
use WePay\OrderScriptAutomation\Model\ResourceModel\CustomerGroupExtension;

class OrderCompleteObserver implements ObserverInterface
{
    const PATH_SCRIPT = 'wepay_orderscriptautomation/path_files/files';
    protected $resultJsonFactory;
    protected $scopeConfig;
    protected $message;
    protected $customerGroupExtension;

    public function __construct(JsonFactory $jsonFactory, ScopeConfigInterface $scopeConfig, ManagerInterface $message, Data $helperData, CustomerGroupExtension $customerGroupExtension) {
        $this->resultJsonFactory = $jsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->message = $message;
        $this->helperData = $helperData;
        $this->customerGroupExtension = $customerGroupExtension;
    }
    public function execute(Observer $observer)
    {
        //log
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();

        if ($order->getStatus() == Order::STATE_COMPLETE)
        {
            $logger->info('Order is complete');
            //run external script
            $customerGroup = $order->getCustomerGroupId();
            $runMode = $this->customerGroupExtension->getCustomScriptMode($customerGroup);

            if($runMode == 2) { // automatic
                $result = $this->resultJsonFactory->create();
                try {
                    $files = $this->scopeConfig->getValue(self::PATH_SCRIPT);
                    $files = BP . $files;
                    if (file_exists($files)) {
                        ob_start();
                        include $files;
                        $output = ob_get_clean();
                    }
                    $this->helperData->flushCache();
                    $this->message->addSuccessMessage(__('Running from Event Observer Successfully!.'));
                    $result->setData(['status' => 'success']);
                } catch (\Exception $e) {
                    $result->setData(['status' => 'error', 'message' => $e->getMessage()]);
                }
                return $result;
            }

        }
    }

}
