<?php
//try {
//} catch (\Throwable $th) {
//   $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/wepay-debug.log');
//   $logger = new \Zend_Log();
//   $logger->addWriter($writer);
//   $logger->info('wepay catch error : '.print_r($th, true));
//}
//log added by prasanna
use Magento\Framework\App\Bootstrap;

$logger->info('External script called');

$logger->info('Order Id: '.$orderId);

?>
