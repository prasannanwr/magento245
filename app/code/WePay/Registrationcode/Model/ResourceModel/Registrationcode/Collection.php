<?php
namespace WePay\Registrationcode\Model\ResourceModel\Registrationcode;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use WePay\Registrationcode\Model\Registrationcode as Model;
use WePay\Registrationcode\Model\ResourceModel\Registrationcode as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
        //parent::_construct(); // TODO: Change the autogenerated stub
    }
}
