<?php

namespace Prasanna\Invitecode\Model\ResourceModel\Attribute;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Prasanna\Invitecode\Model\ResourceModel\Attribute as ResourceModel;
use Prasanna\Invitecode\Model\Attribute as Model;

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
