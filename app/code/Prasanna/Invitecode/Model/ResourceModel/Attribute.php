<?php
namespace Prasanna\Invitecode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attribute extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('attribute_weight', 'entity_id');
    }
}
