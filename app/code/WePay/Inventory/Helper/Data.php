<?php
namespace WePay\Inventory\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{

    protected $cacheTypeList;
    protected $cacheFrontendPool;

    public function __construct(
        \WePay\Inventory\Model\ErrorLogsFactory $errLogsFactory,
        \WePay\Inventory\Model\StatusLogsFactory $statLogsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Config\Storage\WriterInterface $ConfStore,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        ScopeConfigInterface $scopeConfig
    ){
        $this->errLogsFactory = $errLogsFactory;
        $this->statLogsFactory = $statLogsFactory;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->ConfStore = $ConfStore;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->scopeConfig = $scopeConfig;
    }

    public function setErrLogs($responseString)
    {
        try {
            $errLogsColl = $this->errLogsFactory->create();
            $errLogsColl->setData([
                'log' => $responseString
            ]);

            $errLogsColl->save();
        } catch (\Throwable $th) {
            $currentDate = $this->dateTime->gmtDate('d-m-Y');
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/setStatusLogsErr-'.$currentDate.'.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info($th);
        }
    }
  
    public function setStatusLogs($responseString)
    {
        try {
            $statLogsColl = $this->statLogsFactory->create();
            $statLogsColl->setData([
                'log' => $responseString
            ]);

            $statLogsColl->save();
        } catch (\Throwable $th) {
            $currentDate = $this->dateTime->gmtDate('d-m-Y');
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/setStatusLogsErr-'.$currentDate.'.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info($th);
        }
    }

    public function setWgUsername($wg_username)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_username', $wg_username, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setWgPassword($wg_password)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_password', $wg_password, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setWgBearer($mage_bearer)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_mage_bearer', $mage_bearer, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setWgUrl($wg_url_products)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_url_product', $wg_url_products, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setWgUrlStock($wg_url_stock)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_url_stock', $wg_url_stock, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setMageUrlBase($mage_url_base)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_url_base', $mage_url_base, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setForceUpdate($force_update)
    {
        $force_update = $force_update == true ? 1 : 0;
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_force_update', $force_update, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setMageMargin($mage_margin)
    {
        if (!is_int($mage_margin)) {
            $mage_margin = 0;
        }
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_margin', $mage_margin, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setMageMinVal($mage_min_value)
    {
        if (!is_int($mage_min_value)) {
            $mage_min_value = 0;
        }
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_min_value', $mage_min_value, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setMageMaxVal($mage_max_value)
    {
        if (!is_int($mage_max_value)) {
            $mage_max_value = 0;
        }
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_max_value', $mage_max_value, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function setMageProductCountry($mage_product_country)
    {
        $this->ConfStore->save('wepay_inventory/general/wg_inventory_product_country', $mage_product_country, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function flushCache()
    {
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