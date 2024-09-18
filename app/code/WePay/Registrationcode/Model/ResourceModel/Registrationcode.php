<?php
namespace WePay\Registrationcode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Registrationcode extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('registration_code', 'entity_id');
    }
}
