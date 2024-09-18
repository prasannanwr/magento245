<?php

namespace WePay\Registrationcode\Model\ResourceModel\Attribute;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use WePay\Registrationcode\Model\ResourceModel\Attribute as ResourceModel;
use WePay\Registrationcode\Model\Attribute as Model;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
        parent::_construct(); // TODO: Change the autogenerated stub
    }

    public function addAttributeToFilter($attributeCode, $condition = null)
    {
        $this->addFieldToFilter($attributeCode, $condition);
        return $this;
    }
}
