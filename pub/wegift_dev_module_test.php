<?php
die("wegift dev module test script");
exit;
//try {
//} catch (\Throwable $th) {
//   $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/wepay-debug.log');
//   $logger = new \Zend_Log();
//   $logger->addWriter($writer);
//   $logger->info('wepay catch error : '.print_r($th, true));
//}
//log added by prasanna
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
$logger = new \Zend_Log();
$logger->addWriter($writer);
$logger->info('External script called');
return true;

date_default_timezone_set(ini_get('date.timezone'));

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$conf = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
$sesData = $objectManager->get('\Magento\Framework\Session\SessionManagerInterface');

$GLOBALS['wg_username']= $conf->getValue('wepay_inventory/general/wg_inventory_username');
$GLOBALS['wg_password']= $conf->getValue('wepay_inventory/general/wg_inventory_password');
$GLOBALS['mage_bearer']= 'Authorization: Bearer ' . $conf->getValue('wepay_inventory/general/wg_inventory_mage_bearer');
$GLOBALS['wg_url_products']= $conf->getValue('wepay_inventory/general/wg_inventory_url_product');
$GLOBALS['wg_url_stock']= $conf->getValue('wepay_inventory/general/wg_inventory_url_stock');
$GLOBALS['mage_url_base']= $conf->getValue('wepay_inventory/general/wg_inventory_url_base');
$mage_url_categories= $GLOBALS['mage_url_base'] . '/rest/V1/categories';
$mage_url_products= $GLOBALS['mage_url_base'] . '/rest/V1/products/?searchCriteria[pageSize]=0';

//$GLOBALS['force_update'] = $conf->getValue('wepay_inventory/general/wg_inventory_force_update');

    if (isset($sesData->getData()['cron_one_force_update'])) {
        $GLOBALS['force_update'] = $sesData->getData()['cron_one_force_update'];
        $sesData->unsetData('cron_one_force_update');
        $logger->info('CRON ONE - sesData force_update : '.$GLOBALS['force_update']);
    }elseif (isset($sesData->getData()['cron_two_force_update'])) {
        $GLOBALS['force_update'] = $sesData->getData()['cron_two_force_update'];
        $sesData->unsetData('cron_two_force_update');
        $logger->info('CRON TWO - sesData force_update : '.$GLOBALS['force_update']);
    }else{
        $GLOBALS['force_update'] = $conf->getValue('wepay_inventory/general/wg_inventory_force_update');
        $logger->info('MANUAL PROCESS - Data Last Form Update force_update : '.$GLOBALS['force_update']);
    }

$GLOBALS['mage_margin'] = $conf->getValue('wepay_inventory/general/wg_inventory_margin');
$GLOBALS['mage_min_value'] = $conf->getValue('wepay_inventory/general/wg_inventory_min_value');
$GLOBALS['mage_max_value'] = $conf->getValue('wepay_inventory/general/wg_inventory_max_value');
$GLOBALS['wg_product_country'] = $conf->getValue('wepay_inventory/general/wg_inventory_product_country');

$GLOBALS['mageHelp'] = $objectManager->get('WePay\Inventory\Helper\Data');



error_log('['.date("M j, Y, g:i a").'] '."Variable1 " . $GLOBALS['wg_username'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable2 " . $GLOBALS['wg_password'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable3 " . $GLOBALS['mage_bearer'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable4 " . $GLOBALS['wg_url_products'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable5 " . $GLOBALS['wg_url_stock'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable6 " . $GLOBALS['mage_url_base'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable7 " . $GLOBALS['force_update'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable8 " . $GLOBALS['mage_margin'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable9 " . $GLOBALS['mage_min_value'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable10 " . $GLOBALS['mage_max_value'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable11 " . $GLOBALS['wg_product_country'] . "\n", 3, "/var/log/WePay-errors.log");
error_log(date("M j Y, g:i a").' '."Variable1 " . $GLOBALS['wg_username'] . "\n", 3, "/var/log/WePay-errors.log");
error_log(date("M j Y g:i a").' '."Variable1 " . $GLOBALS['wg_username'] . "\n", 3, "/var/log/WePay-errors.log");
error_log('['.date("M j Y g:i a").'] '."Variable1 " . $GLOBALS['wg_username'] . "\n", 3, "/var/log/WePay-errors.log");


error_log('['.date("F j, Y, g:i a e O").'] '."Variable1 " . $GLOBALS['wg_username'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable2 " . $GLOBALS['wg_password'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable3 " . $GLOBALS['mage_bearer'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable4 " . $GLOBALS['wg_url_products'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable5 " . $GLOBALS['wg_url_stock'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable6 " . $GLOBALS['mage_url_base'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable7 " . $GLOBALS['force_update'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable8 " . $GLOBALS['mage_margin'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable9 " . $GLOBALS['mage_min_value'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable10 " . $GLOBALS['mage_max_value'] . "\n", 3, "/var/log/WePay-status.log");
error_log('['.date("F j, Y, g:i a e O").'] '."Variable11 " . $GLOBALS['wg_product_country'] . "\n", 3, "/var/log/WePay-status.log");

error_log('['.date("F j, Y, g:i a e O").'] '."Class Test1 " . $GLOBALS['wg_product_country'] . "\n", 3, "/var/log/WePay-status.log");

$GLOBALS['mage_result_categories'] = getMageApi($mage_url_categories);
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test2 " . $GLOBALS['mage_result_categories'] . "\n", 3, "/var/log/WePay-status.log");
$GLOBALS['mage_result_products'] = getMageApi($mage_url_products);
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test3 " . substr($GLOBALS['mage_result_products'],0,500) . "\n", 3, "/var/log/WePay-status.log");
$GLOBALS['wg_result_products'] = getWGApi($GLOBALS['wg_url_products']);
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test4 " . substr($GLOBALS['wg_result_products'],0,500) . "\n", 3, "/var/log/WePay-status.log");
$globalvalchangetest = changeGlobalVal();
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test5 " . $globalvalchangetest . "\n", 3, "/var/log/WePay-status.log");




function getMageApi($mage_url)
{
   global $GLOBALS;
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test1_function " . $GLOBALS['mage_bearer'] . "\n", 3, "/var/log/WePay-status.log");
   $mage_curl = curl_init();
   curl_setopt($mage_curl, CURLOPT_URL,$mage_url);
   curl_setopt($mage_curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $GLOBALS['mage_bearer'] ));
   curl_setopt($mage_curl, CURLOPT_CUSTOMREQUEST, "GET");
   curl_setopt($mage_curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
   curl_setopt($mage_curl, CURLOPT_RETURNTRANSFER,1);
   $mage_result=curl_exec ($mage_curl);
   $mage_status_code = curl_getinfo($mage_curl, CURLINFO_HTTP_CODE);   //get status code
   if ($mage_status_code != '200'){
      error_log('['.date("M j Y g:i a").'] '.$mage_url . "\n", 3, "/var/log/WePay-errors.log");
      error_log('['.date("M j Y g:i a").'] '.$mage_status_code . "\n", 3, "/var/log/WePay-errors.log");
      error_log('['.date("M j Y g:i a").'] '.$mage_result . "\n", 3, "/var/log/WePay-errors.log");
      //$this->mageHelp->setErrLogs($mage_url );
      //$this->mageHelp->setErrLogs($mage_status_code );
      //$this->mageHelp->setErrLogs($mage_result );
      exit();
   }
   curl_close ($mage_curl);

   return $mage_result;
}

function getWGApi($wg_url)
{
   global $GLOBALS;
   $wg_username = $GLOBALS['wg_username'];
   $wg_password = $GLOBALS['wg_password'];
error_log('['.date("F j, Y, g:i a e O").'] '."Class Test2_function " . $GLOBALS['wg_password'] . "\n", 3, "/var/log/WePay-status.log");
   $wg_curl = curl_init();
   curl_setopt($wg_curl, CURLOPT_URL,$wg_url);
   curl_setopt($wg_curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
   curl_setopt($wg_curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($wg_curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
   curl_setopt($wg_curl, CURLOPT_USERPWD, "$wg_username:$wg_password");
   $wg_result=curl_exec ($wg_curl);
   $wg_status_code = curl_getinfo($wg_curl, CURLINFO_HTTP_CODE);   //get status code
   if ($wg_status_code != '200'){
      error_log('['.date("M j Y g:i a").'] '.$wg_url . "\n", 3, "/var/log/WePay-errors.log");
      error_log('['.date("M j Y g:i a").'] '.$wg_status_code . "\n", 3, "/var/log/WePay-errors.log");
      error_log('['.date("M j Y g:i a").'] '.$wg_result . "\n", 3, "/var/log/WePay-errors.log");
      //$this->mageHelp->setErrLogs($wg_url );
      //$this->mageHelp->setErrLogs($wg_status_code );
      //this->$GLOBALS['mageHelp']->setErrLogs($wg_result );

      exit();
   }

   curl_close ($wg_curl);

   return $wg_result;
}

function changeGlobalVal()
{
   global $GLOBALS;
   $GLOBALS['mage_result_categories'] = 'Changed mage_result_categories';
   error_log('['.date("F j, Y, g:i a e O").'] '."Class Test3_function " . $GLOBALS['mage_result_categories'] . "\n", 3, "/var/log/WePay-status.log");
$GLOBALS['mageHelp']->setErrLogs($GLOBALS['mage_result_categories']);
$GLOBALS['mageHelp']->setStatusLogs($GLOBALS['mage_result_categories']);
   return $GLOBALS['mage_result_categories'];
}

error_log('['.date("F j, Y, g:i a e O").'] '."Class Test4_function " . $GLOBALS['mage_result_categories'] . "\n", 3, "/var/log/WePay-status.log");

?>
