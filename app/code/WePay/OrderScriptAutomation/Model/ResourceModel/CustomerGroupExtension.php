<?php
namespace WePay\OrderScriptAutomation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerGroupExtension extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_group_extension', 'entity_id');
    }

    public function getCustomScriptMode($customerGroupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable(), 'custom_script_mode')->where('entity_id = ?', $customerGroupId);
        return $connection->fetchOne($select);
    }

    public function saveCustomScriptMode($customerGroupId, $customFieldValue)
    {
        //log
//        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
//        $logger = new \Zend_Log();
//        $logger->addWriter($writer);
//        $logger->info('custom field value with resource model: '.$customFieldValue);
        $connection = $this->getConnection();
        $connection->insertOnDuplicate($this->getMainTable(), ['entity_id' => $customerGroupId, 'custom_script_mode' => $customFieldValue]);
    }
}
