<?php
namespace WePay\OrderScriptAutomation\Plugin;

class ManualButton
{
    public function beforeSetLayout(\Magento\Sales\Block\Adminhtml\Order\View $view)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $logger->info('Manual buttton plugin called');
        $message ='Are you sure you want to do this?';
        $url = $view->getUrl('wepay_orderscriptautomation/runnow/index').'?order_id='.$view->getOrderId();
        $logger->info($url);
        if ($view) {
            $view->addButton(
                'custom_script_btn',
                [
                    'label' => __('Run Script'),
                    //'onclick' => 'setLocation(\'' . $this->urlBuilder->getUrl('wepay_orderscriptautomation/runnow/index")', ['order_id' => $orderViewBlock->getOrderId()]) . '\')',
                    'onclick' => "confirmSetLocation('{$message}', '{$url}')",
                    'class' => 'custom-button-class'
                ]
            );
        }


    }

}
