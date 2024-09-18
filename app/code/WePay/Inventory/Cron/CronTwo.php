<?php
namespace WePay\Inventory\Cron;

use WePay\Inventory\Helper\Data as helperData;

/**
 * custom extension 
 */
class CronTwo
{
    const PATH_SCRIPT = 'wepay_inventory/path_files/files';
    const ACTIVE = 'wepay_inventory/cron_two/checkbox';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Session\SessionManagerInterface $session,
        helperData $helperData
    ){
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->helperData = $helperData;
    }

    public function execute()
    {   
        try {
            $isActive = $this->scopeConfig->getValue(self::ACTIVE);
            if ($isActive) {
                $this->setWpForceUpdate();
                $files = $this->scopeConfig->getValue(self::PATH_SCRIPT);
                if (file_exists($files)) {
                    ob_start();
                    include $files;
                    $output = ob_get_clean();
                }
                $this->helperData->flushCache();
            }
        } catch (\Throwable $th) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/cronForceTwoErr.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info($th);
        }
    }

    public function setWpForceUpdate()
    {
        $cronTwoVariable = $this->scopeConfig->getValue('wepay_inventory/cron_two/wg_inventory_crontwo_variable');
        if ($cronTwoVariable == 'true') {
            $cronTwoVariable = true;
        }elseif ($cronTwoVariable == 'false') {
            $cronTwoVariable = false;
        }
        //$this->helperData->setForceUpdate(false);
        $this->session->start();
        $this->session->setData('cron_two_variable', $cronTwoVariable);
    }
}