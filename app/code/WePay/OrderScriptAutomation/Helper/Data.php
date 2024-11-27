<?php
namespace WePay\OrderScriptAutomation\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{

    protected $cacheTypeList;
    protected $cacheFrontendPool;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Config\Storage\WriterInterface $ConfStore,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        ScopeConfigInterface $scopeConfig
    ){
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->ConfStore = $ConfStore;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->scopeConfig = $scopeConfig;
    }

    public function flushCache()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('Flush cache');
        $types = array(
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl','eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        );
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
