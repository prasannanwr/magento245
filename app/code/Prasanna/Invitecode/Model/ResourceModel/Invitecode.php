<?php
namespace Prasanna\Invitecode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Invitecode extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('invite_code', 'entity_id');
    }
}
